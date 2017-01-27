<?php

namespace Heidi\Core;

class PostTypes
{
	/**
	 * Wrapper for register_post_type().
	 *
	 * @param string $name     Post type key, must not exceed 20 characters.
	 * @param array  $config   Better look into register_post_type() function.
	 * @param string $singular Optional. Default singular name.
	 * @param string $multiple Optional. Default multiple name.
	 */
	public static function addPostType( $name, $config, $singular = 'Entry', $multiple = 'Entries' ) {
		$domain = HEIDI_TEXT_DOMAIN;

		if ( ! isset( $config['labels'] ) ) {
			$config['labels'] = array(
				'name' => __( $multiple, $domain ),
				'singular_name' => __( $singular, $domain ),
				'not_found' => __( 'No ' . $multiple . ' Found', $domain ),
				'not_found_in_trash' => __( 'No ' . $multiple . ' found in Trash', $domain ),
				'edit_item' => __( 'Edit ', $singular, $domain ),
				'search_items' => __( 'Search ' . $multiple, $domain ),
				'view_item' => __( 'View ', $singular, $domain ),
				'new_item' => __( 'New ' . $singular, $domain ),
				'add_new' => __( 'Add New', $domain ),
				'add_new_item' => __( 'Add New ' . $singular, $domain ),
			);
		}

		register_post_type( $name, $config );
	}

}
