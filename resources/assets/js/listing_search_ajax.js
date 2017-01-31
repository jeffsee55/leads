jQuery(document).ready(function ($) {

    $('.runListingSearch').click(function(e) {
        e.preventDefault();

        console.log('hey');

        $(this).parents('form').append('<input type="hidden" name="action" value="runListingSearch"');
        // $(this).parents('form').submit();

    })

});
