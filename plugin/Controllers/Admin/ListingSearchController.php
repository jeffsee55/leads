<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\PostTypes;
use Heidi\Core\Controller;

class ListingSearchController extends Controller
{
    public function registerListingAlerts()
    {
        $config = [
            'description' => 'Listing Alerts',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-search',
            'has_archive' => true,
            'capability_type' => 'post',
            // 'capabilities' => [
            //   'create_posts' => 'do_not_allow',
            // ],
            'map_meta_cap' => true,
        ];
        PostTypes::addPostType( 'listing_alert', $config, 'Listing Alert', 'Listing Alerts' );
    }
}
