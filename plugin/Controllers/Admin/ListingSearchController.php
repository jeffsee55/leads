<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\PostTypes;
use Heidi\Core\Controller;
use Heidi\Plugin\Models\ListingSearchDisplayFilters;
use Heidi\Core\Q4_Listings_List_Table;

class ListingSearchController extends Controller
{
    public function __construct()
    {
        $displayFilters= new ListingSearchDisplayFilters;
        $displayFilters->addFilters();
        add_action( 'acf/render_field/type=message', [$displayFilters, 'renderRecentEmails'], 10, 3 );
    }

    public function runTestSearch()
    {
        wp_send_json_success('4 results');
    }

    public function registerListingSearch()
    {
        $config = [
            'description' => 'Listing Search',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => false,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-search',
            'has_archive' => true,
            'capabilities' => array(
                'edit_posts'             => 'manage_listing_searches',
                'edit_others_posts'      => 'manage_listing_searches',
                'publish_posts'          => 'manage_listing_searches',
                'delete_published_posts' => 'manage_listing_searches',
                'delete_others_posts'    => 'manage_listing_searches',
                'edit_published_posts'   => 'manage_listing_searches'
            ),
            'map_meta_cap' => true,
        ];
        PostTypes::addPostType( 'listing_search', $config, 'Listing Search', 'Listing Searches' );
    }

    function getEmailedListings() {
        $listing_ids = isset($_GET['listing_ids']) ? $_GET['listing_ids'] : [];
        $listing_ids = explode(',', $listing_ids);
        $query = new \WP_Query(
            [
                'post_type' => 'listing',
                'post__in' => $listing_ids
            ]
        );

        $table = $this->buildListingTable($query->get_posts(), []);

        wp_send_json_success($table);
    }

    private function buildListingTable($posts, $actions)
    {
        $wp_list_table = new Q4_Listings_List_Table('listing');

        $wp_list_table->setBulkActions($actions);

        $wp_list_table->prepare_items($posts);

        ob_start();
        $wp_list_table->display();
        return ob_get_clean();
    }


}
