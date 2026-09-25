<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * POS Cashiers / Staff list table, built on the native WordPress WP_List_Table.
 * Replaces the old jQuery Bootgrid grid while keeping every feature:
 *  - list users, "All Users" / "Staff Only" views
 *  - search, sorting (ID), pagination
 *  - mark / remove a user as POS staff (via bulk actions, persisted to _op_allow_pos)
 */
class OP_Cashiers_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( array(
            'singular' => 'op_user',
            'plural'   => 'op_users',
            'ajax'     => false,
        ) );
    }

    public function get_columns() {
        return array(
            'id'         => __( 'ID', 'openpos' ),
            'name'       => __( 'Name', 'openpos' ),
            'user_email' => __( 'Email', 'openpos' ),
            'pos_staff'  => __( 'POS Staff', 'openpos' ),
        );
    }

    protected function get_sortable_columns() {
        return array( 'id' => array( 'ID', false ) );
    }

    protected function get_current_display() {
        return ( isset( $_REQUEST['op-display'] ) && $_REQUEST['op-display'] === 'staff' ) ? 'staff' : 'user';
    }

    protected function get_views() {
        $display = $this->get_current_display();
        $base    = admin_url( 'admin.php?page=op-cashiers' );
        $views   = array();
        $views['all'] = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( $base ),
            ( 'user' === $display ) ? 'current' : '',
            __( 'All Users', 'openpos' )
        );
        $views['staff'] = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( add_query_arg( 'op-display', 'staff', $base ) ),
            ( 'staff' === $display ) ? 'current' : '',
            __( 'Staff Only', 'openpos' )
        );
        return $views;
    }

    public function column_id( $item ) {
        return (int) $item['id'];
    }

    public function column_name( $item ) {
        $out = '<strong>' . esc_html( $item['user_login'] ) . '</strong>';
        if ( ! empty( $item['roles'] ) ) {
            $out .= '<p class="op-user-roles">' . esc_html( implode( ', ', $item['roles'] ) ) . '</p>';
        }
        return $out;
    }

    public function column_user_email( $item ) {
        return $item['user_email'] ? '<a href="mailto:' . esc_attr( $item['user_email'] ) . '">' . esc_html( $item['user_email'] ) . '</a>' : '&mdash;';
    }

    public function column_pos_staff( $item ) {
        $on = ! empty( $item['allow_pos'] );
        return '<label class="op-switch' . ( $on ? ' is-on' : '' ) . '">'
            . '<input type="checkbox" class="op-staff-toggle" data-user="' . (int) $item['id'] . '"' . ( $on ? ' checked' : '' ) . '>'
            . '<span class="op-switch__track"><span class="op-switch__knob"></span></span>'
            . '<span class="op-switch__text">' . ( $on ? esc_html__( 'Yes', 'openpos' ) : esc_html__( 'No', 'openpos' ) ) . '</span>'
            . '</label>';
    }

    public function column_default( $item, $column_name ) {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
    }

    public function no_items() {
        _e( 'No users found.', 'openpos' );
    }

    public function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = $this->get_items_per_page( 'op_cashiers_per_page', 20 );
        $current  = $this->get_pagenum();
        $offset   = ( $current - 1 ) * $per_page;

        $orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'ID';
        $order   = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'ASC';
        if ( 'ID' !== $orderby ) {
            $orderby = 'ID';
        }
        $order = ( strtoupper( $order ) === 'DESC' ) ? 'DESC' : 'ASC';

        $search  = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
        $display = $this->get_current_display();
        $roles   = apply_filters( 'op_allow_user_roles', array() );

        $args = array(
            'count_total' => true,
            'number'      => $per_page,
            'offset'      => $offset,
            'orderby'     => $orderby,
            'order'       => $order,
            'fields'      => array( 'ID', 'display_name', 'user_email', 'user_login', 'user_status' ),
        );
        if ( 'staff' === $display ) {
            $args['meta_key']   = '_op_allow_pos';
            $args['meta_value'] = 1;
        }
        if ( ! empty( $roles ) ) {
            $args['role__in'] = $roles;
        }
        if ( $search ) {
            $args['search'] = '*' . $search . '*';
        }

        $user_query = new WP_User_Query( $args );
        $users      = $user_query->get_results();
        $total      = (int) $user_query->get_total();

        $rows = array();
        foreach ( $users as $user ) {
            $uid       = (int) $user->ID;
            $allow_pos = get_user_meta( $uid, '_op_allow_pos', true );
            $userdata  = get_userdata( $uid );
            $rows[]    = array(
                'id'         => $uid,
                'user_login' => $user->user_login,
                'user_email' => $user->user_email,
                'roles'      => ( $userdata && ! empty( $userdata->roles ) ) ? $userdata->roles : array(),
                'allow_pos'  => $allow_pos ? 1 : 0,
            );
        }

        $this->items = $rows;
        $this->set_pagination_args( array(
            'total_items' => $total,
            'per_page'    => $per_page,
            'total_pages' => $per_page > 0 ? ceil( $total / $per_page ) : 0,
        ) );
    }
}
