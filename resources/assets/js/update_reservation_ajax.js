(function( $ ) {
    'use strict';

    $(document).on("click", "#q4vr_update_reservation_ajax", function(e) {
        e.preventDefault();

        $(this).addClass('disabled');

        submitForm();
    });

    $(document).on("change", "#travelInsurance", function(e) {
        submitForm();
    });

    function submitForm() {

        var form = $('#bookingForm').formSerialize();

        $('#updateReservation').empty();

        $('label[for="travel_insurance"]').append('<i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>');

        var url = VRAjax.ajaxurl + '?' + form + '&action=q4vr_stay_reservation';

        $.get( url, function(response) {

            $('.payment-summary').replaceWith(response.data.summary);

            $('#updateReservation').empty();

            $('#updateReservation').append('<div class="well success">' + response.data.message + '</div>');

            $('#q4vr_update_reservation_ajax').removeClass('disabled');
        });
    }

})( jQuery );
