<?php

namespace Heidi\Core;

use Heidi\Core\Q4_List_Table;

class Q4_Listings_Alert_List_Table extends Q4_List_Table
{
    public function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'title'    => __( 'Title', 'heidi' ),
            'alert_day'    => __( 'Alerts', 'heidi' ),
            'email'    => __( 'Email Viewed', 'heidi' ),
            'modified'    => __( 'Modified', 'heidi' ),
        ];

        return $columns;
    }

    public function column_default( $item, $column_name ) {
        if( $column_name == 'title' )
            return '<strong><a href="' . get_edit_post_link($item->ID) . '">' . $item->post_title . '</a></strong>';
        if( $column_name == 'alert_day' )
            return get_post_meta($item->ID, 'alert_day', true);
        if( $column_name == 'email' )
            return 'Never';
        if( $column_name == 'modified' )
            return $item->post_modified_gmt;

    }

    public function no_items() {
        _e( 'No listings alerts created.', 'heidi' );
    }

    function extra_tablenav($which)
    {
        if($which == 'top')
        {
            echo '<div class="tablenav-pages"><span class="displaying-num">Items: ' . count($this->items) . '</span></div>';
        }
    }
}
