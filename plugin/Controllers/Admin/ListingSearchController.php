<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\PostTypes;
use Heidi\Core\Controller;

class ListingSearchController extends Controller
{
    public function __construct()
    {
        add_filter('acf/load_value/name=lead', [$this, 'addLeadToFields'], 10, 3);
        add_filter('acf/load_value', [$this, 'convertSlugs'], 10, 3);
        add_filter( 'manage_edit-listing_search_columns', [$this, 'manageColumns'] );
        add_filter( 'manage_listing_search_posts_custom_column', [$this, 'manageCustomColumns'], 10, 3 );
        add_filter( 'manage_edit-listing_search_sortable_columns', [$this, 'sortableColumns'] );
    }

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

    public function registerListingSearch()
    {
        $config = [
            'description' => 'Listing Search',
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
        PostTypes::addPostType( 'listing_search', $config, 'Listing Search', 'Listing Searches' );
    }

    function addLeadToFields( $value, $post_id, $field )
    {
        if($value == null)
        {
            global $post;
            $value = $post->post_author;

            if(isset($_GET['user_id']))
                $value = $_GET['user_id'];
        }
        return $value;
    }

    function convertSlugs( $value, $post_id, $field )
    {
        // $slug = get_post_meta($post_id, $field, true);
        // $term = get_term_by('slug', $slug, $field);
        // if($term)
        //     return $term->term_id;
        //
        // return $value;
    }

    public function saveLeadAsAuthor($post_id)
    {
        $lead = get_field('lead', $post_id);

        remove_action('save_post', [$this, 'saveLeadAsAuthor']);

        if ( !empty($lead ) ) {
            $args = [
                'ID'=>$post_id,
                'post_author'=>$lead['ID']
            ];

            wp_update_post( $args );
        }

        add_action('save_post', [$this, 'saveLeadAsAuthor']);
    }

    function manageCustomColumns($column, $post_id)
    {
        global $post;
        switch($column)
        {
            case 'alert_day' :
                echo get_post_meta($post_id, 'alert_day', true);
                break;
            case 'lead' :
                $lead = get_userdata($post->post_author);
                echo $lead->user_login;
                break;
            default :
                break;
        }
    }

    function manageColumns($columns) {
        $columns['alert_day'] = 'Alert Day';
        $columns['lead'] = 'Lead';
        unset($columns['date']);

        return $columns;
    }

}
