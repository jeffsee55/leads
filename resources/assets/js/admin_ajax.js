jQuery(document).ready(function ($) {

	function ajaxAddAdminPanel(count, schema) {

		$.ajax({
			url: ajaxurl,
            dataType: 'json',
			data: {
				action: 'add_admin_panel',
				index: count,
				schema: schema
			},
			success: renderPanel
		});
	}

    $(document).on('click', '#add_table', function(event) {

		event.preventDefault();

        var count = $('.q4vr-admin-panel-can-add').length;

		var schema = $(this).data('schema');;

		ajaxAddAdminPanel(count, schema);

	});

    function renderPanel(response)
    {
        $(response.data).insertBefore($('#add-more-panel'));

		addRemoveButtons();
    }


	function addRemoveButtons() {

		$('.q4vr-admin-panel-can-add').each(function() {
			if($(this).find('.admin-panel-remove').length === 0)
			{
				$(this).find('h2.hndle').append('<a class="admin-panel-remove" href="#">Remove</a>');
			}
		});

		$('.admin-panel-remove').click(function(e) {
			e.preventDefault();

			$(this).parents('.postbox').remove();
		});
	}

	addRemoveButtons();
});
