<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\PostTypes;
use Heidi\Core\Controller;

class UserTableController extends Controller
{
    public function addFilters()
    {
        add_filter( 'manage_users_custom_column', [$this, 'ucpc_manage_users_custom_column'], 10, 3 );
        add_filter( 'manage_users_columns', [$this, 'ucpc_manage_users_columns'] );
        add_filter( 'manage_users_sortable_columns', [$this, 'manage_users_sortable_columns'] );

    }
    public function leadQuery($user_query)
    {
        if(isset($_GET['orderby']) && $_GET['orderby'] == 'listing_search')
        {
            $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

            $user_query->query_from = "FROM wp_users
                LEFT OUTER JOIN wp_posts
                ON wp_users.id = wp_posts.post_author AND wp_posts.post_type = 'listing_search'";

            $user_query->query_where = "GROUP BY wp_users.id";

            $user_query->query_orderby = " ORDER BY Count(wp_posts.post_author) " . $order . ", wp_users.user_login ASC";

        } elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'simplefavorites') {

            $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

            $user_query->query_from = "FROM wp_users
                LEFT OUTER JOIN wp_usermeta
                ON wp_users.ID = wp_usermeta.user_id AND wp_usermeta.meta_key = 'simplefavorites'";

            $user_query->query_where = "";

            $user_query->query_orderby = "ORDER BY CAST(substring_index(substring_index(wp_usermeta.meta_value, ':{', 3), ':', -1) AS INT)" . $order . ", wp_users.user_login ASC";
        }
    }

    function manage_users_sortable_columns($columns) {
        $columns['listing_search'] = 'listing_search';
        $columns['name'] = 'name';
        $columns['favorited'] = 'simplefavorites';

        return $columns;
    }

    function ucpc_get_posts_by_author_sql($post_type, $full = TRUE, $post_author = NULL) {
    	global $user_ID, $wpdb;

    	// Private posts
    	if ($post_type == 'post') {
    		$cap = 'read_private_posts';
    	// Private pages
    	} elseif ($post_type == 'page') {
    		$cap = 'read_private_pages';
    	// Private custom posts
    	} else {
    		$cap = 'read_private_pages';
    	}

    	if ($full) {
    		if (is_null($post_author)) {
    			$sql = $wpdb->prepare('WHERE post_type = %s AND ', $post_type);
    		} else {
    			$sql = $wpdb->prepare('WHERE post_author = %d AND post_type = %s AND ', $post_author, $post_type);
    		}
    	} else {
    		$sql = '';
    	}

    	$sql .= "(post_status = 'publish'";

    	if (current_user_can($cap)) {
    		// Does the user have the capability to view private posts? Guess so.
    		$sql .= " OR post_status = 'private'";
    	} elseif (is_user_logged_in()) {
    		// Users can view their own private posts.
    		$id = (int) $user_ID;
    		if (is_null($post_author) || !$full) {
    			$sql .= " OR post_status = 'private' AND post_author = $id";
    		} elseif ($id == (int)$post_author) {
    			$sql .= " OR post_status = 'private'";
    		} // else none
    	} // else none

    	$sql .= ')';

    	return $sql;
    }

    /**
     * Adds a new column to the user listing.
     *
     * @param string $output 		Not used.
     * @param bool $column_name 	New column name.
     * @param int $user_id 			User ID.
     * @return string 				The html column.
     */
    function ucpc_manage_users_custom_column($output = '', $column_name, $user_id) {
        if( $column_name == 'listing_search' )
        {
            global $wpdb;
        	$post_type = 'listing_search';

        	$post_type_label = get_post_type_object($post_type)->label;

            $where = $this->ucpc_get_posts_by_author_sql( $post_type, true, $user_id );
            $result = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

            return '<a href="' . admin_url("edit.php?post_type=".$post_type."&author=$user_id") . '" title="'.__($post_type_label).'">' . $result . '</a>';
        } elseif ($column_name == 'favorited')
        {
            $listing_ids = get_user_meta($user_id, 'simplefavorites', true);
            if($listing_ids)
            {
                $listing_ids = $listing_ids[0]['posts'];
                return '<strong><a href="' . admin_url("edit.php?post_type=listing&filter=favorites_by_ids&listing_ids=") . implode(',', $listing_ids) . '">' . count($listing_ids) . ' Favorited</strong></a><br>' . get_user_favorites_count($user_id) . ' Active';
            }
            return 'No favorites';
        }
    }

    /**
     * Renames the new user column.
     *
     * @param string $columns 	Columns array.
     * @return string 			Modified columns array.
     */
    function ucpc_manage_users_columns($columns) {
        $columns['favorited'] = 'Favorited';
        $columns['listing_search'] = 'Listing Searches';
        unset($columns['posts']);
        unset($columns['role']);
        unset($columns['email']);

        return $columns;
    }
}
