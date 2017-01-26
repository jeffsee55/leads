(function( $ ) {
    'use strict';

    // form inputs
    var givenName = $('#bookingForm input[name="traveler[first_name]"]');
    var familyName = $('#bookingForm input[name="traveler[last_name]"]');
    var email = $('#bookingForm input[name="traveler[email]"]');
    var emailConfirm = $('#bookingForm input[name="email_confirm"]');
    var tel = $('#bookingForm input[name="traveler[phone]"]');
    var telAlt = $('#bookingForm input[name="tel_alt"]');
    var addressLine1 = $('#bookingForm input[name="mailing_address[address1]"]');
    var addressLine2 = $('#bookingForm input[name="mailing_address[address2]"]');
    var city = $('#bookingForm input[name="mailing_address[city]"]');
    var state = $('#bookingForm select[name="mailing_address[state]"]');
    var zip = $('#bookingForm input[name="mailing_address[postal_code]"]');
    var terms = $('#bookingForm input[name="terms"]');
    var ccName = $('#bookingForm input[name="cc_name"]');
    var ccNum = $('#bookingForm input[name="cc_num"]');
    var ccExpMonth = $('#bookingForm select[name="cc_expires_month"]');
    var ccExpYear = $('#bookingForm select[name="cc_expires_year"]');
    var cvc = $('#bookingForm input[name="cc_cvc"]');

    $('#resultSelect').on('change', function() {
        var selectValue = $(this).val();

        givenName.val('Jane');
        familyName.val('Doe');
        email.val('jane@example.com');
        emailConfirm.val('jane@example.com');
        tel.val('1 234 5678');
        telAlt.val('1 234 5670');
        addressLine1.val('123 Main St.');
        city.val('Charleston');
        state.val('SC');
        zip.val('29401');
        terms.prop('checked', 'checked');

        if(selectValue == 'credit_card_failure') {
            ccName.val('Invalid Card');
            ccNum.val('4111111111112345');
        } else {
            ccName.val('Jane Doe');
            ccNum.val('4111111111111111');
        }
        ccExpMonth.val('12');
        ccExpYear.val('20');
        cvc.val('123');
    });


})( jQuery );
