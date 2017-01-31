<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\PostTypes;
use Heidi\Core\Controller;
use Heidi\Core\Q4_List_Table;
use Heidi\Core\Q4_Leads_List_Table;

class UserTableController extends Controller
{
    public $currentUser;
    public $currentRole;

    public function setCurrentUser()
    {
        $this->currentUser = wp_get_current_user();
        $roles = get_userdata($this->currentUser->ID)->roles;
        $this->currentRole = $roles ? array_shift($roles) : false;
    }

    public function hideUsersNavForAgents()
    {
        if($this->currentRole == 'agent') {
            echo '<script>jQuery(document).ready(function ($) {$(".users-php").find("ul.subsubsub .all, ul.subsubsub .agent, ul.subsubsub .super_agent").remove()});</script>';
        } elseif($this->currentRole == 'super_agent') {
            echo '<script>jQuery(document).ready(function ($) {$(".users-php").find("ul.subsubsub .all").remove()});</script>';
        }
    }

    function changeUsersMenuLabels()
    {
        if($this->currentRole == 'agent')
        {
            global $menu;
            global $submenu;
            $menu[70][0] = 'Leads';
            $submenu['users.php'][5][0] = 'Leads';
        }
    }

    public function addFilters()
    {
        add_filter( 'manage_users_custom_column', [$this, 'ucpc_manage_users_custom_column'], 10, 3 );
        add_filter( 'manage_users_columns', [$this, 'ucpc_manage_users_columns'] );
        add_filter( 'manage_users_sortable_columns', [$this, 'manage_users_sortable_columns'] );

    }
    public function leadQuery($user_query)
    {
        global $wpdb;

        $user_query->query_from = "FROM wp_users";

        if($this->currentRole == 'agent')
        {
            $agent_id = $this->currentUser->ID;
            $user_query->query_from .= " INNER JOIN wp_usermeta AS wpm ON wp_users.ID = wpm.user_id
            AND (( wpm.meta_key = '_agent_id' AND wpm.meta_value = $agent_id ))";
        } elseif($this->currentRole == 'super_agent' && (isset($_GET['role']) && $_GET['role'] == 'lead')) {
            $agent_id = $this->currentUser->ID;
            $user_query->query_from .= " INNER JOIN wp_usermeta AS wpm ON wp_users.ID = wpm.user_id
            AND (( wpm.meta_key = '_agent_id' AND wpm.meta_value = $agent_id ))";
        } else {
            // $user_query->query_from .= " INNER JOIN wp_usermeta AS wpm ON wp_users.ID = wpm.user_id";
        }

        if(isset($_GET['orderby']) && $_GET['orderby'] == 'listing_search')
        {
            $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

            $user_query->query_from .= "
            LEFT OUTER JOIN wp_posts
            ON (( wp_users.id = wp_posts.post_author AND wp_posts.post_type = 'listing_search'))";

            $user_query->query_orderby = " GROUP BY wp_posts.post_author, wp_users.user_login ORDER BY Count(wp_posts.post_author) " . $order . ", wp_users.user_login ASC";

        } elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'simplefavorites') {
            $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

            $user_query->query_from .= "
            LEFT OUTER JOIN wp_usermeta
            ON wp_users.ID = wp_usermeta.user_id AND wp_usermeta.meta_key = 'simplefavorites'";

            $user_query->query_orderby = "ORDER BY CAST(substring_index(substring_index(wp_usermeta.meta_value, ':{', 3), ':', -1) AS INT) " . $order . ", wp_users.user_login ASC";
        } elseif(isset($_GET['orderby']) && $_GET['orderby'] == '_last_visited') {
            $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

            $user_query->query_from .= "
            LEFT OUTER JOIN wp_usermeta
            ON wp_users.ID = wp_usermeta.user_id AND wp_usermeta.meta_key = '_last_visited'";

            $user_query->query_orderby = "ORDER BY wp_usermeta.meta_value $order, wp_users.user_login ASC";
        }

        $user_query->query_where = "WHERE 1=1";
        if(isset($_GET['role']) && !empty($_GET['role']))
        {
            $role = $_GET['role'];
            $user_query->query_from .= " INNER JOIN wp_usermeta AS meta ON wp_users.ID = meta.user_id AND ( meta.meta_key = 'wp_capabilities' AND meta.meta_value LIKE '%{$role}%' )";
        }

        if(isset($user_query->query_vars['search']) && !empty($user_query->query_vars['search']))
        {
            $s = trim($user_query->query_vars['search'], '*');
            $user_query->query_where .= " AND (user_login LIKE '%$s%' OR user_url LIKE '%$s%' OR user_email LIKE '%$s%' OR user_nicename LIKE '%$s%' OR display_name LIKE '%$s%')";
        }
    }

    function manage_users_sortable_columns($columns) {
        $columns['listing_search'] = 'listing_search';
        $columns['name'] = 'name';
        $columns['favorited'] = 'simplefavorites';
        $columns['most_recent'] = '_last_visited';

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

            return $result;
        } elseif ($column_name == 'favorited') {
            $listing_ids = get_user_meta($user_id, 'simplefavorites', true);
            if($listing_ids)
            {
                $listing_ids = $listing_ids[0]['posts'];
                return '<strong><a href="' . admin_url("edit.php?post_type=listing&filter=favorites_by_ids&listing_ids=") . implode(',', $listing_ids) . '">' . count($listing_ids) . ' Favorited</strong></a><br>' . get_user_favorites_count($user_id) . ' Active';
            }
            return 'No favorites';
        } elseif ($column_name == 'most_recent') {
            $lastVisited = get_user_meta($user_id, '_last_visited', true);
            return $lastVisited ? date('H:m:s m/d/Y', $lastVisited) : 'N / A';
        }
    }

    /**
    * Renames the new user column.
    *
    * @param string $columns 	Columns array.
    * @return string 			Modified columns array.
    */
    function ucpc_manage_users_columns($columns) {
        // if(isset($_GET['role']) && $_GET['role'] == 'lead')
        // {
            $columns['favorited'] = 'Favorited';
            $columns['listing_search'] = 'Listing Searches';
            $columns['most_recent'] = 'Most Recent';
            unset($columns['posts']);
            unset($columns['role']);
            unset($columns['email']);
        // }

        return $columns;
    }
}
