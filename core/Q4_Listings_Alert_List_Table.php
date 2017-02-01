<?php

namespace Heidi\Core;

use Heidi\Core\Q4_List_Table;

class Q4_Listings_Alert_List_Table extends Q4_List_Table
{
    public $alert_days = [
        1 => '1st',
        2 => '2nd',
        3 => '3rd',
        4 => '4th',
        5 => '5th',
        6 => '6th',
        7 => '7th',
        8 => '8th',
        9 => '9th',
        10 => '10th',
        11 => '11th',
        12 => '12th',
        13 => '13th',
        14 => '14th',
        15 => '15th',
        16 => '16th',
        17 => '17th',
        18 => '18th',
        19 => '19th',
        20 => '20th',
        21 => '21st',
        22 => '22nd',
        23 => '23rd',
        24 => '24th',
        25 => '25th',
        26 => '26th',
        27 => '27th',
        28 => '28th',
        29 => '29th',
        30 => '30th',
        31 => '31st',
        32 => 'Sundays',
        33 => 'Mondays',
        34 => 'Tuesdays',
        35 => 'Wednesdays',
        36 => 'Thursdays',
        37 => 'Fridays',
        38 => 'Saturdays',
        39 => 'Never',
    ];

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
            return $this->alert_days[get_field('alert_day', $item->ID)];
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
