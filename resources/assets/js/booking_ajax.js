(function( $ ) {
    'use strict';

    $('#bookingForm').validator( {
        feedback: {
            success: 'form-success',
            error: 'form-failure'
        }
    } );

    $('input.cc-number').payment('formatCardNumber');
    $('input.cc-exp').payment('formatCardExpiry');
    $('input.cc-csc').payment('formatCardCVC');
    $('#sameAddress').change(function() {
        $('#billingAddress').collapse('toggle');
    });

})( jQuery );
