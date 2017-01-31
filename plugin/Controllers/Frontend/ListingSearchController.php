<?php

namespace Heidi\Plugin\Controllers\Frontend;

use Heidi\Core\Controller;
use Heidi\Plugin\Models\ListingSearch;

class ListingSearchController extends Controller
{

    public function runListingSearch()
    {
        $slugs = isset($_GET['withSlugs']) ? true : false;

        $query = ListingSearch::buildQuery($_GET['listing_search_id'], $slugs);
        wp_redirect($query);
    }
}
