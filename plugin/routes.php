<?php

$router->group('Admin',
    [
        'LeadProfileController' => [
            'admin_init'  => 'addFilters',
            'profile_update' => 'updateLead',
            'admin_enqueue_scripts' => 'enqueueLeadScripts',
            'show_user_profile' => 'addLeadFields',
            'edit_user_profile' => 'addLeadFields',
            'acf/render_field/type=message' => 'loadListingAlerts',
            'wp_ajax_get_listing_alerts' => 'getListingAlerts',
            'wp_ajax_get_favorites' => 'getFavorites',
            'wp_ajax_get_recent' => 'getRecent'
        ],
        'ListingSearchController' => [
            'init'  => 'registerListingAlerts',
        ],
    ]
);

$router->group('Services',
    [
        'MailerController' => [
            'phpmailer_init'  => 'registerSMTP',
        ],
        'TrackerController' => [
            'wp'  => 'trackListingView',
        ],
    ]
);
