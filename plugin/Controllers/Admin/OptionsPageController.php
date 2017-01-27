<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\Controller;

class OptionsPageController extends Controller
{
    public function addOptionsPages()
    {
        if( function_exists('acf_add_options_page') ) {

        	acf_add_options_page(array(
        		'page_title' 	=> 'Q4 Listings',
        		'menu_title'	=> 'Q4 Listings',
        		'menu_slug' 	=> 'q4-listings-settings',
        		'capability'	=> 'edit_posts',
        		'redirect'		=> false
        	));

        	acf_add_options_sub_page(array(
        		'page_title' 	=> 'Q4 Leads',
        		'menu_title'	=> 'Leads',
        		'parent_slug'	=> 'q4-listings-settings',
        	));

        	acf_add_options_sub_page(array(
        		'page_title' 	=> 'Q4 Listings',
        		'menu_title'	=> 'Listings',
        		'parent_slug'	=> 'q4-listings-settings',
        	));

        }
    }
}
