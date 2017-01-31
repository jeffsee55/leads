jQuery(document).ready(function ($) {
    var user_id = $("input[name='user_id']").val();
    getListingAlerts(user_id);

	function getListingAlerts(user_id) {

		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'get_listing_alerts',
                user_id: user_id
			},
			success: renderAlerts
		});
	}

    function renderAlerts(response)
    {
        $('#listingAlerts').append(response.data);
    }

    getFavorites(user_id);

	function getFavorites(user_id) {

		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'get_favorites',
                user_id: user_id
			},
			success: renderFavorites
		});
	}

    function renderFavorites(response)
    {
        $('#favorites').append(response.data);
    }

    getRecent(user_id);

	function getRecent(user_id) {

		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'get_recent',
                user_id: user_id
			},
			success: renderRecent
		});
	}

    function renderRecent(response)
    {
        $('#recent').append(response.data);
    }

    $(".viewEmailedListings").click(function(e) {
        e.preventDefault();

        var listing_ids = $(this).data('listings');

        var listing_search_id = $("input[name='post_ID']").val();

        getEmailedListings(listing_search_id, listing_ids);

    })

	function getEmailedListings(listing_search_id, listing_ids) {

		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'get_emailed_listings',
                listing_search_id: listing_search_id,
                listing_ids: listing_ids
			},
			success: renderEmailed
		});
	}

    function renderEmailed(response)
    {
        $('#emailedListings').html(response.data);
    }

    var hideRows = [
        'user-rich-editing-wrap',
        'user-comment-shortcuts-wrap',
        'show-admin-bar user-admin-bar-front-wrap',
        'user-url-wrap',
        'user-description-wrap',
        'user-profile-picture'
    ];
    for(row of hideRows)
    {
        console.log(row);
        $('.' + row).hide();
    }
    $('#wordpress-seo').hide();
    $('#wordpress-seo').next('table').hide();

    $('#runTestSearch').click(function(e) {

        e.preventDefault();

        $(this).addClass('disabled');

        var listing_search_id = $(this).data('listing-search-id');
		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'run_test_search',
                listing_search_id: listing_search_id
			},
			success: renderResult
		});

        $(this).removeClass('disabled');

    })

    function renderResult(response)
    {
        console.log(response);
        $('#test_search_result').html(response.data);
    }


});
