<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * POS Products (barcode) list table built on the native WP_List_Table, replacing
 * the old jQuery Bootgrid grid while keeping every feature:
 *  - columns: ID, Barcode (editable), Thumbnail, Product Name, Price, Action
 *  - search (barcode / SKU / name, with variable-product expansion)
 *  - sorting (ID), pagination
 *  - bulk "Save barcodes" and "Print barcodes" (processed before output, see Admin)
 *  - single-row Edit / Print Barcode actions
 *
 * Bulk actions are handled in Openpos_Admin::products_handle_bulk() (on load-)
 * so they can redirect (PRG) before any output is sent.
 */
class OP_Products_List_Table extends WP_List_Table {

    /** @var Openpos_Core */
    protected $core;
    /** @var OP_Settings */
    protected $settings_api;
    /** @var Openpos_Admin */
    protected $admin;

    public function __construct( $core, $settings_api, $admin ) {
        $this->core         = $core;
        $this->settings_api = $settings_api;
        $this->admin        = $admin;

        parent::__construct( array(
            'singular' => 'op_product',
            'plural'   => 'op_products',
            'ajax'     => false,
        ) );
    }

    public function get_columns() {
        return array(
            'cb'              => '<input type="checkbox" />',
            'id'              => __( 'ID', 'openpos' ),
            'barcode'         => __( 'Barcode', 'openpos' ),
            'product_thumb'   => __( 'Thumbnail', 'openpos' ),
            'post_title'      => __( 'Product Name', 'openpos' ),
            'formatted_price' => __( 'Price', 'openpos' ),
            'action'          => __( 'Action', 'openpos' ),
        );
    }

    protected function get_sortable_columns() {
        return array(
            'id' => array( 'ID', false ),
        );
    }

    protected function get_bulk_actions() {
        return array(
            'save'  => __( 'Save barcodes', 'openpos' ),
            'print' => __( 'Print barcodes', 'openpos' ),
        );
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="product[]" value="%d" />', $item['id'] );
    }

    public function column_default( $item, $column_name ) {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
    }

    public function no_items() {
        _e( 'No products found.', 'openpos' );
    }

    public function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $allow_types = $this->core->getPosProductTypes();

        $per_page = $this->get_items_per_page( 'op_products_per_page', 20 );
        $current  = $this->get_pagenum();
        $offset   = ( $current - 1 ) * $per_page;

        $orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'date';
        $order   = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'DESC';
        if ( ! in_array( $orderby, array( 'ID', 'date' ), true ) ) {
            $orderby = 'date';
        }
        $order = ( strtoupper( $order ) === 'ASC' ) ? 'ASC' : 'DESC';

        $search  = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
        $ignores = apply_filters( 'admin_op_products_ignores', array(), $this->admin );

        $args = array(
            'posts_per_page'   => $per_page,
            'offset'           => $offset,
            'current_page'     => $current,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => $orderby,
            'order'            => $order,
            'exclude'          => $ignores,
            'post_type'        => $this->core->getPosPostType(),
            'post_status'      => $this->core->getDefaultProductPostStatus(),
            'suppress_filters' => false,
        );

        $total       = 0;
        $posts_array = array();

        if ( $search ) {
            $args['s']  = $search;
            $product_id = $this->core->getProductIdByBarcode( $search );

            if ( $product_id ) {
                $_tmp_product = wc_get_product( $product_id );
                if ( $_tmp_product && $_tmp_product->get_type() === 'variable' && in_array( 'variation', $allow_types ) ) {
                    foreach ( $_tmp_product->get_children() as $child_id ) {
                        $posts_array[] = get_post( $child_id );
                    }
                }
            } else {
                $_tmp_product = wc_get_product( $search );
                if ( $_tmp_product ) {
                    if ( in_array( $_tmp_product->get_type(), $allow_types ) ) {
                        $posts_array[] = get_post( $_tmp_product->get_id() );
                    }
                } else {
                    $product_id = wc_get_product_id_by_sku( $search );
                    if ( $product_id ) {
                        $_tmp_product = wc_get_product( $product_id );
                        if ( $_tmp_product && $_tmp_product->get_type() === 'variable' && in_array( 'variation', $allow_types ) ) {
                            foreach ( $_tmp_product->get_children() as $child_id ) {
                                $posts_array[] = get_post( $child_id );
                            }
                        }
                    }
                }
            }
        }

        if ( empty( $posts_array ) ) {
            $posts       = $this->core->getProducts( $args );
            $posts_array = $posts['posts'];
            $total       = (int) $posts['total'];
        } else {
            $total = count( $posts_array );
        }

        $this->items = $this->format_rows( $posts_array, $allow_types );

        $this->set_pagination_args( array(
            'total_items' => $total,
            'per_page'    => $per_page,
            'total_pages' => $per_page > 0 ? ceil( $total / $per_page ) : 0,
        ) );
    }

    /**
     * Build display rows. Same data & formatting as the previous AJAX handler.
     */
    protected function format_rows( $posts_array, $allow_types ) {
        $rows                  = array();
        $parent_products_cache = array();

        foreach ( $posts_array as $post ) {
            if ( is_a( $post, 'WP_Post' ) ) {
                $product_id = $post->ID;
            } else {
                $product_id = $post->get_id();
                $post       = get_post( $product_id );
            }
            $_product = wc_get_product( $product_id );
            if ( ! $_product ) {
                continue;
            }
            $type = $_product->get_type();
            if ( ! in_array( $type, $allow_types ) ) {
                continue;
            }

            $thumb    = wc_placeholder_img_src() ? wc_placeholder_img() : '';
            $thumb_id = get_post_thumbnail_id( $product_id );
            if ( $thumb_id ) {
                $props = wc_get_product_attachment_props( $thumb_id, $post );
                $thumb = get_the_post_thumbnail( $post->ID, 'shop_thumbnail', array(
                    'title' => $props['title'],
                    'alt'   => $props['alt'],
                ) );
            }

            $edit_link = get_edit_post_link( $product_id );
            if ( $type === 'variation' ) {
                $parent_id = $post->post_parent;
                if ( ! isset( $parent_products_cache[ $parent_id ] ) ) {
                    $parent_products_cache[ $parent_id ] = wc_get_product( $parent_id );
                }
                $parent_product  = $parent_products_cache[ $parent_id ];
                $parent_thumb_id = get_post_thumbnail_id( $parent_id );
                if ( $parent_thumb_id && ! $thumb_id ) {
                    $props = wc_get_product_attachment_props( $parent_thumb_id, $parent_product );
                    $thumb = get_the_post_thumbnail( $parent_id, 'shop_thumbnail', array(
                        'title' => $props['title'],
                        'alt'   => $props['alt'],
                    ) );
                }
                $edit_link = get_edit_post_link( $parent_id );
            }

            $action_html  = '<a href="' . esc_url( $edit_link ) . '">' . __( 'edit', 'openpos' ) . '</a>';
            $action_html .= '<a href="' . esc_url( admin_url( 'admin.php?page=op-products&action=print-barcode&id=' . $product_id ) ) . '" class="print-barcode-product-btn">' . __( 'Print Barcode', 'openpos' ) . '</a>';
            $action_html  = '<div class="action-row">' . $action_html . '</div>';

            $price          = $_product->get_price();
            $barcode        = $this->core->getBarcode( $product_id );
            $barcode_input  = '<input type="text" name="barcode[' . $product_id . ']" class="op-barcode-input" value="' . esc_attr( $barcode ) . '">';

            $post_title = esc_html( $post->post_title );
            if ( $type === 'variation' ) {
                $variation_label_attributes = array();
                foreach ( $_product->get_attributes() as $vk => $v ) {
                    $variation_label_attributes[] = $_product->get_attribute( $vk );
                }
                $post_title .= '<p>' . esc_html( implode( ',', $variation_label_attributes ) ) . '</p>';
            }

            if ( ! $price ) {
                $price = 0;
            }

            $tmp = array(
                'id'              => $product_id,
                'post_title'      => $post_title,
                'action'          => $action_html,
                'regular_price'   => $_product->get_regular_price(),
                'sale_price'      => $_product->get_sale_price(),
                'price'           => $price,
                'barcode'         => $barcode_input,
                'formatted_price' => wc_price( $price ),
                'product_thumb'   => $thumb,
            );

            $tmp = apply_filters( 'op_admin_products_data', $tmp, $this->admin );
            if ( $tmp && ! empty( $tmp ) ) {
                $rows[] = $tmp;
            }
        }

        return $rows;
    }
}
