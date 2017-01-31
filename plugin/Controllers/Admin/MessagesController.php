<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\Controller;
use Heidi\Plugin\Controllers\Services\MailerController;
use Heidi\Plugin\Models\ListingSearch;
use Heidi\Plugin\Models\MailNotifier;

class MessagesController extends Controller
{
    public function handleMessage($message_id)
    {
        global $wpdb;

        $fields = get_fields($message_id);

        if(isset($fields['interested_in']))
        {
            $fields['title'] = 'Potential ' . $fields['interested_in'];
            $wpdb->update( $wpdb->posts, ['post_title' => $field['title']], ['ID', $message_id]);
        }

        if(isset($_POST['listing_id']))
            $fields['listing_id'] = $_POST['listing_id'];

        MailerController::sendContactMessage($fields);
    }

    public function registerMessages() {
        $args = array(
            'public'              => true, // bool (default is FALSE)
            'description'         => __( 'Messages', 'q4_leads' ), // string
            'publicly_queryable'  => true, // bool (defaults to 'public').
            'exclude_from_search' => false, // bool (defaults to 'public')
            'show_in_nav_menus'   => false, // bool (defaults to 'public')
            'show_ui'             => true, // bool (defaults to 'public')
            'show_in_menu'        => true, // bool (defaults to 'show_ui')
            'show_in_admin_bar'   => false, // bool (defaults to 'show_in_menu')
            'menu_position'       => 7,
            'menu_icon'           => 'dashicons-email',
            'can_export'          => true, // bool (defaults to TRUE)
            'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')
            'capability_type'     => 'post', // string|array (defaults to 'post')
            'map_meta_cap'        => true, // bool (defaults to FALSE)
            'supports' => array(
                'revisions',
            ),
            'capabilities' => array(
                'edit_posts'             => 'manage_messages',
                'edit_others_posts'      => 'manage_messages',
                'publish_posts'          => 'manage_messages',
                'delete_published_posts' => 'manage_messages',
                'delete_others_posts'    => 'manage_messages',
                'edit_published_posts'   => 'manage_messages'
            ),
            'rewrite' => array(
                'slug'            => 'messages'
            ),
            'labels' => array(
                'name'               => __( 'Messages',                   'q4-leads' ),
                'singular_name'      => __( 'Message',                    'q4-leads' ),
                'menu_name'          => __( 'Messages',                   'q4-leads' ),
                'name_admin_bar'     => __( 'Messages',                   'q4-leads' ),
                'add_new'            => __( 'Add New',                    'q4-leads' ),
                'add_new_item'       => __( 'Add New Message',            'q4-leads' ),
                'edit_item'          => __( 'Edit Message',               'q4-leads' ),
                'new_item'           => __( 'New Message',                'q4-leads' ),
                'view_item'          => __( 'View Message',               'q4-leads' ),
                'search_items'       => __( 'Search Messages',            'q4-leads' ),
                'not_found'          => __( 'No messages found',          'q4-leads' ),
                'not_found_in_trash' => __( 'No messages found in trash', 'q4-leads' ),
                'all_items'          => __( 'All Messages',               'q4-leads' ),
                'archive_title'      => __( 'Message',                    'q4-leads' ),
            ),
        );

        register_post_type( 'message', $args );
    }
}
