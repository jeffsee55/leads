jQuery(document).ready(function ($) {
	$(document).on( 'click', '.nav-tab-wrapper a', function() {

		$('.nav-tab-wrapper a').removeClass('nav-tab-active');

		$(this).addClass('nav-tab-active');

		var id = $(this).attr('href');

		$('section').hide();

		$(id).show();

		return false;

	})

	$('.checkbox input[type="checkbox"]').change(function() {

		toggleCheckbox(this);

	});


	$('.checkbox input[type="checkbox"]').each(function() {

		toggleCheckbox(this);

	});


	function toggleCheckbox(element) {

		if(element.checked) {

			$(element).parents('.checkbox').find('select').attr('disabled', false);

		} else {

			$(element).parents('.checkbox').find('select')[0].selectedIndex = 0;

			$(element).parents('.checkbox').find('select').attr('disabled', true);

		}
	}


	$(document).on('click', '[data-add]', function() {
		addArgumentInput(this);
	});


	$(document).on('click', '[data-remove]', function() {

		if($(this).parents('tr').find('li').length == 1) {

			$(this).parents('tr').addClass('hidden');

		}

		$(this).parent('li').remove();
	});


	function addArgumentInput(element) {

		var li = $(element).parent('li').clone();

		count = $(element).parents('ul').children('li').length;

		li = incrementIndex($(li).html(), count);

		$(element).parents('ul').append(li);

	};


	function incrementIndex(s, count) {
		s = s.replace(/(\[arguments\]\[)\d+?\]/g, function(index){

			index = index.replace(/\D/g,'');

			index = '[arguments][' + count + ']';

			return index;

		});

		return '<li>' + s + '</li>';

	}


	$(document).on('change', '[data-select]', function() {

		loadConditionalInputs($(this));

	});


	function loadConditionalInputs(element) {

		var inputName = $(element).parents('tr').attr('class');

		if(inputName == 'input_type') {

			if($(element).val() == 'checkbox' || $(element).val() == 'select') {

				$(element).parents('tbody').find('tr.arguments').removeClass('hidden');

			} else {

				var argumentRow = $(element).parents('tbody').find('tr.arguments');

				$(argumentRow).addClass('hidden');

				$(argumentRow).find('input').val('');

			}
		}

	};

});
