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
});
