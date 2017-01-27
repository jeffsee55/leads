<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\Controller;
use Heidi\Core\Q4_List_Table;
use Heidi\Core\Q4_Listings_Alert_List_Table;
use Heidi\Core\Q4_Listings_List_Table;

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
                'post_type' => 'listing_search',
                'author' => $_GET['user_id'],
            ]
        );
        $actions = ['bulk-delete' => 'Delete'];
        $table = $this->buildAlertTable($query->get_posts(), $actions);

        wp_send_json_success($table);
    }

    function getFavorites() {
        $listings = get_user_favorites($_GET['user_id']);
        $query = new \WP_Query(
            [
                'post_type' => 'listing',
                'post__in' => ! empty($listings) ? $listings : [0]
            ]
        );
        $orderedListings = $this->orderByRecent($listings, $query->get_posts());

        $table = $this->buildListingTable($orderedListings, []);

        wp_send_json_success($table);
    }

    function getRecent() {
        $listings = get_user_meta($_GET['user_id'], '_viewed_listings', true);
        $query = new \WP_Query(
            [
                'post_type' => 'listing',
                'post__in' => ! empty($listings) ? $listings : [0]
            ]
        );
        $orderedListings = $this->orderByRecent($listings, $query->get_posts());

        $table = $this->buildListingTable($orderedListings, []);

        wp_send_json_success($table);
    }

    public function orderByRecent($listings, $queryListings)
    {
        foreach($listings as $listingID)
        {
            $items = array_filter($queryListings, function($queryListing) use ($listingID) {
                return $queryListing->ID == $listingID;
            });
            $orderedListings[] = array_shift($items);
        }

        return array_reverse($orderedListings);
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

    private function buildAlertTable($posts, $actions)
    {
        $wp_list_table = new Q4_Listings_Alert_List_Table('listing_search');

        $wp_list_table->setBulkActions($actions);

        $wp_list_table->prepare_items($posts);

        ob_start();
        $wp_list_table->display();
        return ob_get_clean();
    }

    public function addLeadFields($user)
    {
        $user_id = $user ? $user->ID : null;
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update User"><span class="acf-spinner"></span></p>';
        echo '<h1>';
        echo 'Listing Searches <a href="/wp-admin/post-new.php?post_type=listing_search&user_id=' . $user_id . '" class="page-title-action">Add New</a>';
        echo '</h1>';
        echo '<div id="listingAlerts"></div>';
        echo '<h1>Favorites</h1>';
        echo '<div id="favorites" style="position: relative"></div>';
        echo '<h1>Recently Viewed</h1>';
        echo '<div id="recent" style="position: relative"></div>';
    }

    private function count_user_posts_by_type( $userid, $post_type = 'post' ) {
    	global $wpdb;

    	$where = get_posts_by_author_sql( $post_type, true, $userid );

    	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

      	return apply_filters( 'get_usernumposts', $count, $userid );
    }
}
