<?php

namespace Heidi\Core;

class Taxonomies
{
	/**
	 * Wrapper for register_post_type().
	 *
	 * @param string $name     Post type key, must not exceed 20 characters.
	 * @param array  $config   Better look into register_post_type() function.
	 * @param string $singular Optional. Default singular name.
	 * @param string $multiple Optional. Default multiple name.
	 */
	public static function add( $name, $postType = 'post', $config, $singular = 'Entry', $multiple = 'Entries' ) {
		$domain = Heidi_TEXT_DOMAIN;

		if ( ! isset( $config['labels'] ) ) {
			$config['labels'] = array(
	            'name' => __( $singular, $domain),
	            'singular_name' => __( $multiple, $domain),
	            'add_new_item' => __( 'Add New ' . $singular, $domain),
	            'all_items' => __( 'All ' . $multiple, $domain),
	            'menu_name' => __( $multiple, $domain),
			);
		}

        register_taxonomy($name, $postType, $config);
	}

}
