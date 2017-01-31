<?php

namespace Heidi\Core;

use Heidi\Core\Q4_List_Table;

class Q4_Leads_List_Table extends Q4_List_Table
{
    public function get_columns() {
        $columns = [
            'email'    => __( 'Title', 'heidi' ),
            'name'    => __( 'Thumbnail', 'heidi' ),
            'listing_search'    => __( 'Searches', 'heidi' ),
            'favorited'    => __( 'Favorites', 'heidi' )
        ];

        return $columns;
    }

    public function column_default( $item, $column_name ) {
        return 'Hey';
    }

    public function no_items() {
        _e( 'No leads avaliable.', 'heidi' );
    }

    function extra_tablenav($which)
    {
        if($which == 'top')
        {
            echo '<div style="position: absolute; top: -30px; right: 10px;"><span class="displaying-num">Items: ' . count($this->items) . '</span></div>';
        }
    }
}
