<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Cash Transactions list table, built on top of the native WordPress WP_List_Table
 * so it matches the standard WordPress / WooCommerce admin table look & behaviour.
 *
 * Keeps every feature of the previous jQuery Bootgrid implementation:
 *  - columns: ID, Ref, IN, OUT, Method, Register, By, Created At
 *  - search, pagination, sorting (ID / date)
 *  - "All" / "Custom transactions" view filters (source_type)
 *  - register / warehouse context filtering (from query string)
 *  - bulk delete (adjusts the cash balance, same as before)
 */
class OP_Transactions_List_Table extends WP_List_Table {

    /** @var Openpos_Core */
    protected $core;
    /** @var OP_Register */
    protected $register_class;
    /** @var OP_Warehouse */
    protected $warehouse_class;

    public function __construct( $core, $register_class, $warehouse_class ) {
        $this->core            = $core;
        $this->register_class  = $register_class;
        $this->warehouse_class = $warehouse_class;

        parent::__construct( array(
            'singular' => 'op_transaction',
            'plural'   => 'op_transactions',
            'ajax'     => false,
        ) );
    }

    public function get_columns() {
        return array(
            'cb'           => '<input type="checkbox" />',
            'id'           => __( 'ID', 'openpos' ),
            'title'        => __( 'Ref', 'openpos' ),
            'in_amount'    => __( 'IN', 'openpos' ),
            'out_amount'   => __( 'OUT', 'openpos' ),
            'payment_name' => __( 'Method', 'openpos' ),
            'register'     => __( 'Register', 'openpos' ),
            'created_by'   => __( 'By', 'openpos' ),
            'created_at'   => __( 'Created At', 'openpos' ),
        );
    }

    protected function get_sortable_columns() {
        return array(
            'id'         => array( 'ID', false ),
            'created_at' => array( 'date', true ),
        );
    }

    protected function get_bulk_actions() {
        return array(
            'delete' => __( 'Delete', 'openpos' ),
        );
    }

    /**
     * "All" / "Custom transactions" filter links (standard WP views).
     */
    protected function get_views() {
        $current = isset( $_REQUEST['source_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['source_type'] ) ) : '';
        $base    = admin_url( 'admin.php?page=op-transactions' );
        $base    = $this->keep_context_url( $base );

        $views = array();
        $views['all'] = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( remove_query_arg( 'source_type', $base ) ),
            ( '' === $current ) ? 'current' : '',
            __( 'All', 'openpos' )
        );
        $views['custom'] = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( add_query_arg( 'source_type', 'custom', $base ) ),
            ( 'custom' === $current ) ? 'current' : '',
            __( 'Custom transactions', 'openpos' )
        );

        return $views;
    }

    /**
     * Preserve register / warehouse context on generated links.
     */
    protected function keep_context_url( $url ) {
        if ( ! empty( $_REQUEST['register'] ) ) {
            $url = add_query_arg( 'register', (int) $_REQUEST['register'], $url );
        }
        if ( ! empty( $_REQUEST['warehouse'] ) ) {
            $url = add_query_arg( 'warehouse', (int) $_REQUEST['warehouse'], $url );
        }
        return $url;
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="transaction[]" value="%d" />', $item['id'] );
    }

    public function column_default( $item, $column_name ) {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
    }

    public function no_items() {
        _e( 'No transactions found.', 'openpos' );
    }

    /**
     * Bulk delete. Mirrors the previous update_transaction_grid() behaviour:
     * adjust the global cash balance by (in - out) then delete the post.
     */
    public function process_bulk_action() {
        if ( 'delete' !== $this->current_action() ) {
            return;
        }
        check_admin_referer( 'bulk-' . $this->_args['plural'] );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        $ids = isset( $_REQUEST['transaction'] ) ? array_map( 'intval', (array) $_REQUEST['transaction'] ) : array();
        foreach ( $ids as $post_id ) {
            if ( get_post_type( $post_id ) !== 'op_transaction' ) {
                continue;
            }
            $in                = get_post_meta( $post_id, '_in_amount', true );
            $out               = get_post_meta( $post_id, '_out_amount', true );
            $total_transaction = ( $in - $out );
            $balance           = get_option( '_pos_cash_balance', 0 );
            $balance          += $total_transaction;
            update_option( '_pos_cash_balance', $balance );
            wp_delete_post( $post_id );
        }
    }

    public function prepare_items() {
        $this->process_bulk_action();

        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = $this->get_items_per_page( 'op_transactions_per_page', 20 );
        $current  = $this->get_pagenum();
        $offset   = ( $current - 1 ) * $per_page;

        $orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'date';
        $order   = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'DESC';
        // Only allow the orderby values we expose as sortable.
        if ( ! in_array( $orderby, array( 'ID', 'date' ), true ) ) {
            $orderby = 'date';
        }
        $order = ( strtoupper( $order ) === 'ASC' ) ? 'ASC' : 'DESC';

        $source_type  = isset( $_REQUEST['source_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['source_type'] ) ) : '';
        $warehouse_id = isset( $_REQUEST['warehouse'] ) ? (int) $_REQUEST['warehouse'] : 0;
        $register_id  = isset( $_REQUEST['register'] ) ? (int) $_REQUEST['register'] : 0;
        $search       = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';

        $args = array(
            'posts_per_page'   => $per_page,
            'offset'           => $offset,
            'orderby'          => $orderby,
            'order'            => $order,
            'post_type'        => array( 'op_transaction' ),
            'post_status'      => 'any',
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
        if ( $source_type ) {
            $meta_query[] = array(
                'key'     => '_source_type',
                'value'   => $source_type,
                'compare' => '=',
            );
        }
        if ( ! empty( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }
        if ( $search ) {
            $args['s'] = $search;
        }

        $final_args = apply_filters( 'admin_transactions_args', $args );
        $query      = new WP_Query( $final_args );

        $this->items = $this->format_rows( $query->get_posts() );

        $total_items = (int) $query->found_posts;
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => $per_page > 0 ? ceil( $total_items / $per_page ) : 0,
        ) );
    }

    /**
     * Build display rows. Same data & formatting as the previous AJAX handler.
     */
    protected function format_rows( $posts_array ) {
        $rows           = array();
        $cashdrawer_key = $this->register_class->get_transaction_meta_key();

        foreach ( $posts_array as $post ) {
            $id       = $post->ID;
            $user_id  = get_post_meta( $id, '_user_id', true );
            $register = __( 'Unknown', 'openpos' );
            $name     = __( 'Unknown', 'openpos' );

            $row_register_id = get_post_meta( $id, $cashdrawer_key, true );
            if ( $row_register_id ) {
                $register_details = $this->register_class->get( $row_register_id );
                if ( $register_details && isset( $register_details['name'] ) ) {
                    $register = $register_details['name'];
                }
            }

            if ( $user_id ) {
                $user = get_user_by( 'ID', $user_id );
                if ( $user ) {
                    $name = $user->display_name;
                }
            }

            $method_code = get_post_meta( $id, '_payment_code', true );
            $method_name = get_post_meta( $id, '_payment_name', true );
            $currency    = get_post_meta( $id, '_currency', true );
            if ( ! $name ) {
                $method_name = $method_code;
            }
            if ( ! $method_name ) {
                $method_name = __( 'Cash', 'openpos' );
            }

            $created_at_time = get_post_meta( $id, '_created_at', true );
            $created_at      = $this->core->render_ago_date_by_time_stamp( $post->post_date );
            // $created_at is trusted HTML from render_ago_date_by_time_stamp(); only the raw stored time string is escaped.
            $created_at_html = '<p>' . $created_at . "</p><p class='pos-local-time'>" . esc_html( $created_at_time ) . '</p>';

            $in_amount  = get_post_meta( $id, '_in_amount', true );
            $out_amount = get_post_meta( $id, '_out_amount', true );

            if ( $currency && isset( $currency['code'] ) ) {
                $price_args = array(
                    'currency'           => $currency['code'],
                    'decimal_separator'  => $currency['decimal_separator'],
                    'thousand_separator' => $currency['thousand_separator'],
                    'decimals'           => $currency['decimal'],
                );
                $in_amount_format  = wc_price( $in_amount, $price_args );
                $out_amount_format = wc_price( $out_amount, $price_args );
            } else {
                $in_amount_format  = wc_price( $in_amount );
                $out_amount_format = wc_price( $out_amount );
            }

            $rows[] = array(
                'id'           => $id,
                'title'        => esc_html( $post->post_title ),
                'in_amount'    => $in_amount_format,
                'out_amount'   => $out_amount_format,
                'payment_name' => esc_html( $method_name ),
                'created_at'   => $created_at_html,
                'register'     => esc_html( $register ),
                'created_by'   => esc_html( $name ),
            );
        }

        return $rows;
    }
}
