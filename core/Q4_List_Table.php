<?php

namespace Heidi\Core;

class Q4_List_Table extends \WP_List_Table
{
    public $screen;
    public $actions = [];

    public function __construct($post_type)
    {
		parent::__construct( [
			'singular' => __( 'Listing Alert', 'heidi' ), //singular name of the listed records
			'plural'   => __( 'Listing Alerts', 'heidi' ), //plural name of the listed records
			'ajax'     => true //should this table support ajax?
		] );
    }

    public function setBulkActions($actions)
    {
        $this->actions = $actions;
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'title':
            return '<strong><a href="' . get_edit_post_link($item->ID) . '">' . $item->post_title . '</a></strong>';
            case 'date':
            return $item->post_date;
            default:
            return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item->ID
        );
    }

    public function no_items() {
        _e( 'No listing alerts avaliable.', 'heidi' );
    }

    public function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'title'    => __( 'Title', 'heidi' ),
            'date'    => __( 'Date', 'heidi' )
        ];

        return $columns;
    }

    public function get_bulk_actions() {
        $actions = [];
        foreach($this->actions as $action => $display){
            $actions[$action] = $display;
        }

        return $actions;
    }

    function display_tablenav( $which )
    {
        if(! empty($this->actions)) :
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>">

                <div class="alignleft actions">
                    <?php $this->bulk_actions(); ?>
                </div>
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                ?>
                <br class="clear" />
            </div>
            <?php
        endif;
    }

    function column_name( $item ) {

        // create a nonce
        // $delete_nonce = wp_create_nonce( 'heidi_delete_listing_alert' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [
            'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
        ];

        return $title . $this->row_actions( $actions );
    }

    // Direct copy of WP_Posts_List_Table except removes global $wp_query
    public function prepare_items($posts) {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $total_items  = $wp_query->found_posts;

        $this->set_pagination_args( [
          'total_items' => $total_items, //WE have to calculate the total number of items
          'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );


        $this->items = $posts;
    }

}
