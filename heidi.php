<?php
/*
Plugin Name: Heidi
Description: A starter plugin using the blade templating engine
Plugin URI:  https://gitlab.com/jeff16/heidi
Gitlab Plugin URI:  https://gitlab.com/jeff16/heidi
Gitlab Branch: staging
Version:     0.0.2
Author:      Jeff See, Q4 Launch
Author URI:  https://q4launch.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: q4vr
Domain Path: /languages
*/

defined('ABSPATH') or die('No script kiddies please!');

define('HEIDI_TEXT_DOMAIN', 'q4vrplugin');
define('HEIDI_VERSION', '0.0.1');

define('HEIDI_PATH', plugin_dir_path(__FILE__));
define('HEIDI_PLUGIN_PATH', HEIDI_PATH . 'plugin/');
define('HEIDI_RESOURCE_PATH', HEIDI_PATH . 'resources/');

define('HEIDI_DIR', plugins_url('', __FILE__));
define('HEIDI_PLUGIN_DIR', HEIDI_DIR . '/plugin/');
define('HEIDI_RESOURCE_DIR', HEIDI_DIR . '/resources/');

require __DIR__ . '/bootstrap.php';

$plugin = getPlugin();


add_action('plugins_loaded', function() {

    Heidi\Core\Router::load('routes.php');

});

register_activation_hook(__FILE__, 'heidi_activate');

function heidi_activate() {
    add_role( 'super_agent', 'Super Agent',
      array(
        'delete_others_pages' => true,
        'delete_others_posts' => true,
        'delete_pages' => true,
        'delete_posts' => true,
        'delete_private_pages' => true,
        'delete_private_posts' => true,
        'delete_published_pages' => true,
        'delete_published_posts' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_private_pages' => true,
        'edit_private_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'manage_categories' => true,
        'manage_links' => true,
        'moderate_comments' => true,
        'publish_pages' => true,
        'read_private_pages' => true,
        'read_private_posts' => true,
        'delete_posts' => true,
        'delete_published_posts' => true,
        'edit_posts' => true,
        'edit_published_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'read' => true,
        'create_users' => true,
        'edit_users' => true,
        'delete_users' => true,
        'manage_listing_searches' => true,
        'edit_listing_searches' => true,
        'manage_listings' => true,
        'edit_listings' => true,
        'manage_messages' => true,
      )
    );
    
    add_role( 'lead', 'Lead', array( 'read' => true ) );

    remove_role( 'subscriber' );
    remove_role( 'author' );
    remove_role( 'contributor' );
}
