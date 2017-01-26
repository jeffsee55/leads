jQuery(document).ready(function ($) {

	$('div.postbox').each(function() {

		addMediaSelectors($(this).attr('id'));

	});

	function addMediaSelectors(id) {

		addMediaSelector($('#' + id));

	}

	function addMediaSelector(table) {
		var rows = table.find('.image-file-input');

		rows.each(function(index) {

			var frame,
				addImgLink = $(this).find('.upload-custom-img'),
				imgContainer = $(this).find('.custom-img-container'),
				imgIdInput = $(this).find('.custom-img-id');

			addImgLink.on( 'click', function( event ){

				event.preventDefault();

				if ( frame ) {

					frame.open();

					return;
				}

				frame = wp.media({

					title: 'Choose an image',

					button: {
						text: 'Use this media'
					},

					multiple: false
				});

				frame.on( 'select', function() {

					var attachment = frame.state().get('selection').first().toJSON();

					imgContainer.find('img').remove();

					imgContainer.append( '<img src="' + attachment.url + '" alt="" style="max-width: 150px;"/>' );

					imgIdInput.val( attachment.id );
				});

				frame.open();
			});
		});

	}
});
