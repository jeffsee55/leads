<?php

namespace Heidi\Plugin\Controllers\Services;

use Heidi\Core\Controller;
use Heidi\Core\Q4_List_Table;

class TrackerController extends Controller
{
    public function trackListingView()
    {
        if(is_singular('listing'))
        {
            global $post;

            $user_id = get_current_user_id();

            $viewed_listings = get_user_meta($user_id, '_viewed_listings', true);

            $viewed_listings[] = $post->ID;

            update_user_meta($user_id, '_viewed_listings', $viewed_listings);
        }
    }
}
