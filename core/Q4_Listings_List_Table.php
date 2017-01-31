<?php

namespace Heidi\Core;

use Heidi\Core\Q4_List_Table;

class Q4_Listings_List_Table extends Q4_List_Table
{
    public function get_columns() {
        $columns = [
            'title'    => __( 'Title', 'heidi' ),
            'thumb'    => __( 'Thumbnail', 'heidi' ),
            'list_price'    => __( 'List Price', 'heidi' ),
            'type'    => __( 'Type', 'heidi' ),
        ];

        return $columns;
    }

    public function column_default( $item, $column_name ) {
        if( $column_name == 'title' )
            return '<strong><a href="' . get_edit_post_link($item->ID) . '">' . $item->post_title . '</a></strong>';
        if( $column_name == 'thumb' )
            return '<img style="width: 100px" src=' . get_post_meta( $item->ID, '_listing_featured_image', true ) . '>';
        if( $column_name == 'list_price' )
            return get_post_meta( $item->ID, '_list_price', true );
        if ($column_name == 'type') {
          $class = get_post_meta( $item->ID, '_listing_class', true );
          echo ucwords(str_replace('_', ' ', $class ));
        }

    }

    public function no_items() {
        _e( 'No listings avaliable.', 'heidi' );
    }

    function extra_tablenav($which)
    {
        if($which == 'top')
        {
            echo '<div style="position: absolute; top: -30px; right: 10px;"><span class="displaying-num">Items: ' . count($this->items) . '</span></div>';
        }
    }
}
