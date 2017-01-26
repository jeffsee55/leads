<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\Controller;
use Heidi\Core\Q4_List_Table;

class LeadProfileController extends Controller
{
    public function addFilters()
    {
        add_filter( 'user_contactmethods', 'removeContactMethods', 10, 1);
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }

    public function enqueueLeadScripts()
    {
        wp_enqueue_script('hide_lead_fields', HEIDI_RESOURCE_DIR . 'assets/js/admin_leads.js', [], HEIDI_VERSION, true);
    }

    public function updateLead($post_id)
    {
        if(isset($_POST['bulk-delete']))
        {
            foreach($_POST['bulk-delete'] as $post_id)
            {
                wp_delete_post( $post_id, true);
            }
        }
    }

    public function removeContactMethods($contactmethods) {
        unset($contactmethods['aim']);
        unset($contactmethods['jabber']);
        unset($contactmethods['website']);
        return $contactmethods;
    }

    function getListingAlerts() {
        $query = new \WP_Query(
            [
                'post_type' => 'listing_alert',
                'meta_query' => [
                    [
                        'key' => 'lead',
                        'value' => $_GET['user_id'],
                        'compare' => '='
                    ]
                ]
            ]
        );
        $actions = ['bulk-delete' => 'Delete'];
        $table = $this->buildTable('listing_alert', $query->get_posts(), $actions);

        wp_send_json_success($table);
    }

    function getFavorites() {
        $listings = get_user_favorites($_GET['user_id']);
        $query = new \WP_Query(
            [
                'post_type' => 'listing',
                'post__in' => $listings
            ]
        );
        $table = $this->buildTable('listing', $query->get_posts(), []);

        wp_send_json_success($table);
    }

    function getRecent() {
        $listings = get_user_meta($_GET['user_id'], '_viewed_listings', true);
        $query = new \WP_Query(
            [
                'post_type' => 'listing',
                'post__in' => $listings
            ]
        );
        $table = $this->buildTable('listing', $query->get_posts(), []);

        wp_send_json_success($table);
    }

    private function buildTable($post_type, $posts, $actions)
    {
        $wp_list_table = new Q4_List_Table($post_type);

        $wp_list_table->setBulkActions($actions);

        $wp_list_table->prepare_items($posts);

        ob_start();
        $wp_list_table->display();
        return ob_get_clean();
    }

    public function addLeadFields()
    {
        echo '<h1>';
        echo 'Listing Alerts <a href="/wp-admin/post-new.php?post_type=listing_alert" class="page-title-action">Add New</a>';
        echo '</h1>';
        echo '<div id="listingAlerts"></div>';
        echo '<h1>Favorites</h1>';
        echo '<div id="favorites"></div>';
        echo '<h1>Recently Viewed</h1>';
        echo '<div id="recent"></div>';
    }
}
