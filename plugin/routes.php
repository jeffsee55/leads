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
            'init'  => 'registerListingSearch',
            'admin_init' => 'setCurrentUser',
            'save_post' => 'saveLeadAsAuthor',
            'wp_ajax_get_emailed_listings' => 'getEmailedListings'
        ],
        'MessagesController' => [
            'init'  => 'registerMessages',
            'acf/save_post' => 'handleMessage'
        ],
        'UserTableController' => [
            'admin_init' => [
                'addFilters',
                'setCurrentUser',
                'changeUsersMenuLabels'
            ],
            'pre_user_query' => 'leadQuery',
            'admin_footer' => 'hideUsersNavForAgents'
        ],
        'OptionsPageController' => [
            'wp_loaded' => 'addOptionsPages',
            'admin_post_convert_listing_searches' => 'convertListingSearches',
            'admin_post_clean_listing_searches' => 'cleanListingSearches',
            'admin_post_add_terms_to_location' => 'addTermsToLocation'
        ]
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

$router->group('Frontend',
    [
        'UserLoginController' => [
            'q4_render_login_form' => 'renderLogin',
            'q4_alert_cta' => 'renderFooterSignUp',
            'admin_post_q4_sign_out' => 'handleSignOut',
            'admin_post_nopriv_q4_sign_up' => 'handleSignUp',
        ],
        'ProfilePageController' => [
            'wp_head'  => 'addHeader',
            'wp' => 'enqueueListingSearchScripts',
        ],
        'ListingSearchController' => [
            'admin_post_run_listing_search' => 'runListingSearch',
        ],
        'SearchFormController' => [
            'q4_render_seachform' => 'render',
        ],
        'MessagesController' => [
            'q4_render_contact_modal' => 'renderModal',
            'q4_render_contact_form' => 'renderForm',
        ],
    ]
);
