(function( $ ) {
    'use strict';

    $('[data-toggle="popover"]').popover()

    $('#advancedSearch').on('show.bs.collapse', function () {

        if($(window).width() < 768) {

            // $('#searchSubmit').addClass('fade');

        }

    })

    $('#advancedSearch').on('hide.bs.collapse', function () {

        if($(window).width() < 768) {

            // $('#searchSubmit').removeClass('fade');

        }

    })

    $('.search-summary-alert.alert-dismissible').on('closed.bs.alert', function () {

        $("input[name='page']").val('');

        var queryVar = $(this).data('query_var_alert');

        var input = $('[data-query_var="' + queryVar + '"]');

        $(input).val('');

        $(input).attr('checked', false);

    })
})( jQuery );
