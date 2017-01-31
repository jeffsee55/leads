jQuery(document).ready(function ($) {

    $('.runListingSearch').click(function(e) {
        e.preventDefault();

        $(this).parents('form').append('<input type="hidden" name="action" value="runListingSearch"');
    })

});
