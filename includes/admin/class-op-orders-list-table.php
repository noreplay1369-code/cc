<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * POS Orders list table built on the native WP_List_Table, replacing the old
 * jQuery Bootgrid grid while keeping every feature:
 *  - columns: ID, Customer, Date, Source, By, Total, Status
 *  - search (POS order number), sorting (ID / date), pagination
 *  - register / warehouse context filtering (from query string)
 *  - HPOS (custom order tables) aware, same as the previous AJAX handler
 */
class OP_Orders_List_Table extends WP_List_Table {

    /** @var Openpos_Core */
    protected $core;
    /** @var OP_Register */
    protected $register_class;
    /** @var OP_Warehouse */
    protected $warehouse_class;
    /** @var OP_Woo_Order */
    protected $order_class;

    public function __construct( $core, $register_class, $warehouse_class, $order_class ) {
        $this->core            = $core;
        $this->register_class  = $register_class;
        $this->warehouse_class = $warehouse_class;
        $this->order_class     = $order_class;

        parent::__construct( array(
            'singular' => 'op_order',
            'plural'   => 'op_orders',
            'ajax'     => false,
        ) );
    }

    public function get_columns() {
        return array(
            'id'         => __( 'ID', 'openpos' ),
            'customer'   => __( 'Customer', 'openpos' ),
            'created_at' => __( 'Date', 'openpos' ),
            'source'     => __( 'Source', 'openpos' ),
            'created_by' => __( 'By', 'openpos' ),
            'total'      => __( 'Total', 'openpos' ),
            'status'     => __( 'Status', 'openpos' ),
        );
    }

    protected function get_sortable_columns() {
        return array(
            'id'         => array( 'ID', false ),
            'created_at' => array( 'date', true ),
        );
    }

    public function column_default( $item, $column_name ) {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
    }

    public function no_items() {
        _e( 'No orders found.', 'openpos' );
    }

    public function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = $this->get_items_per_page( 'op_orders_per_page', 20 );
        $current  = $this->get_pagenum();
        $offset   = ( $current - 1 ) * $per_page;

        $orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'date';
        $order   = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'DESC';
        if ( ! in_array( $orderby, array( 'ID', 'date' ), true ) ) {
            $orderby = 'date';
        }
        $order = ( strtoupper( $order ) === 'ASC' ) ? 'ASC' : 'DESC';

        $warehouse_id = isset( $_REQUEST['warehouse'] ) ? (int) $_REQUEST['warehouse'] : 0;
        $register_id  = isset( $_REQUEST['register'] ) ? (int) $_REQUEST['register'] : 0;
        $search       = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';

        $post_statuses = apply_filters( 'op_orders_statuses', array(
            'wc-processing',
            'wc-pending',
            'wc-completed',
            'wc-refunded',
            'wc-on-hold',
        ) );

        $args = array(
            'posts_per_page'   => $per_page,
            'offset'           => $offset,
            'orderby'          => $orderby,
            'order'            => $order,
            'post_type'        => array( 'shop_order' ),
            'post_status'      => $post_statuses,
            'suppress_filters' => false,
        );

        $meta_query = array();
        if ( $register_id ) {
            $meta_query[] = array(
                'key'     => $this->register_class->get_transaction_meta_key(),
                'value'   => $register_id,
                'compare' => '=',
            );
        }
        if ( $warehouse_id ) {
            $meta_query[] = array(
                'key'     => $this->warehouse_class->get_transaction_meta_key(),
                'value'   => $warehouse_id,
                'compare' => '=',
            );
        }
        $meta_query[] = array(
            'key'     => '_op_order_source',
            'value'   => 'openpos',
            'compare' => '=',
        );
        if ( $search ) {
            $meta_query[] = array(
                'key'     => '_op_order_number_format',
                'value'   => ltrim( $search, '#' ),
                'compare' => 'LIKE',
            );
        }
        $args['meta_query'] = $meta_query;

        $final_args = apply_filters( 'admin_orders_args', $args );

        if ( $this->core->enable_hpos() ) {
            $args['_query_src'] = 'op_order_query';
            $args['paginate']   = true;
            $data_store         = WC_Data_Store::load( 'order' );
            $results            = $data_store->query( $args );
            $posts_array        = $results->orders;
            $total              = (int) $results->total;
        } else {
            $get_posts   = new WP_Query( $final_args );
            $posts_array = $get_posts->get_posts();
            $total       = (int) $get_posts->found_posts;
        }

        // Fallback lookups by order number when nothing matched (same as before).
        if ( $total === 0 && $search ) {
            $tmp_order_id = $this->order_class->get_order_id_from_order_number_format( $search );
            if ( $tmp_order_id ) {
                $posts_array[] = get_post( $tmp_order_id );
                $total         = 1;
            }
        }
        if ( $total === 0 && $search ) {
            $tmp_order_id = $this->order_class->get_order_id_from_number( $search );
            if ( $tmp_order_id ) {
                $posts_array[] = get_post( $tmp_order_id );
                $total         = 1;
            }
        }

        $this->items = $this->format_rows( $posts_array );

        $this->set_pagination_args( array(
            'total_items' => $total,
            'per_page'    => $per_page,
            'total_pages' => $per_page > 0 ? ceil( $total / $per_page ) : 0,
        ) );
    }

    /**
     * Build display rows. Same data & formatting as the previous AJAX handler.
     */
    protected function format_rows( $posts_array ) {
        $rows = array();

        foreach ( $posts_array as $post ) {
            if ( $post instanceof WC_Order ) {
                $id                    = $post->get_id();
                $order                 = $post;
                $register_id           = $order->get_meta( '_pos_order_cashdrawer' );
                $cashier_id            = $order->get_meta( '_op_sale_by_cashier_id' );
                $_op_sale_by_person_id = $order->get_meta( '_op_sale_by_person_id' );
                $_op_order             = $order->get_meta( '_op_order' );
                $view_url              = $order->get_edit_order_url();
            } else {
                if ( ! $post ) {
                    continue;
                }
                $id    = $post->ID;
                $order = wc_get_order( $id );
                if ( ! $order ) {
                    continue;
                }
                $register_id           = get_post_meta( $id, '_pos_order_cashdrawer', true );
                $cashier_id            = get_post_field( 'post_author', $id );
                $_op_sale_by_person_id = get_post_meta( $id, '_op_sale_by_person_id', true );
                $_op_order             = get_post_meta( $id, '_op_order', true );
                $view_url              = $order->get_edit_order_url();
            }

            $register_name = __( 'Unknown', 'openpos' );
            $register      = $this->register_class->get( $register_id );
            if ( ! empty( $register ) ) {
                $register_name = $register['name'];
            }

            $cashier      = get_user_by( 'ID', $cashier_id );
            $cashier_name = 'unknown';
            if ( $cashier ) {
                $cashier_name = $cashier->display_name;
            }
            $seller_name = $cashier_name;
            if ( $_op_sale_by_person_id ) {
                $seller = get_user_by( 'ID', $_op_sale_by_person_id );
                if ( $seller ) {
                    $seller_name = $seller->display_name;
                }
            }

            $by_html  = '<p><b>C:</b> ' . esc_html( $cashier_name ) . '</p>';
            $by_html .= '<p><b>S:</b> ' . esc_html( $seller_name ) . '</p>';

            $created_at       = $this->core->render_order_date_column( $order );
            $created_at_local = '';
            if ( $_op_order && isset( $_op_order['created_at'] ) ) {
                $created_at_local = $_op_order['created_at'];
            }
            $created_at_html = '<p>' . $created_at . '</p>';
            if ( $created_at_local ) {
                $created_at_html .= '<p class="pos-local-time">' . esc_html( $created_at_local ) . '</p>';
            }

            $status_html = '<span class="order_status ' . esc_attr( $order->get_status() ) . '">' . esc_html( $order->get_status() ) . '</span>';

            $order_number_str  = '<p class="op-order-id">' . esc_html( $id ) . '</p>';
            $order_number_str .= '<a class="op-order-number" href="' . esc_url( $view_url ) . '">#' . esc_html( $order->get_order_number() ) . '</a>';

            $customer_name = $order->get_formatted_billing_address();
            if ( ! $customer_name ) {
                $customer_name = __( 'Guest', 'openpos' );
            }

            $rows[] = array(
                'id'         => $order_number_str,
                'customer'   => $customer_name,
                'source'     => esc_html( $register_name ),
                'created_at' => $created_at_html,
                'total'      => $order->get_formatted_order_total(),
                'created_by' => $by_html,
                'status'     => $status_html,
            );
        }

        return $rows;
    }
}
