<?php

namespace Heidi\Plugin\Controllers\Admin;

use Heidi\Core\Controller;
use Heidi\Plugin\Models\ListingSearchConverter;
use Heidi\Plugin\Models\ListingSearchCleaner;

class OptionsPageController extends Controller
{
    public function __construct()
    {
        add_filter('acf/prepare_field/name=convert_listing_searches', [$this, 'renderConvertButton']);
    }

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

    public function renderConvertButton($field)
    {
        $field['instructions'] = '<br><a href="/wp-admin/admin-post.php?action=convert_listing_searches" class="button">Convert</a>';
        $field['instructions'] .= '<br><a href="/wp-admin/admin-post.php?action=clean_listing_searches" class="button">Clean</a>';
        $field['instructions'] .= '<br><a href="/wp-admin/admin-post.php?action=add_terms_to_location" class="button">Add Terms</a>';

        return $field;
    }

    public function convertListingSearches()
    {
        $converter = new ListingSearchConverter();
        $converter->convert();
    }

    public function cleanListingSearches()
    {
        $converter = new ListingSearchCleaner();
        $converter->clean();
    }
    public function addTermsToLocation()
    {
        $converter = new ListingSearchConverter();
        $converter->addTermsToLocation();
    }
}
