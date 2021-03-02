(function(window, $) {

    // USE STRICT
    "use strict";

    var form = document.getElementById('wpsd-donation-form-id');
    var stripe = Stripe(wpsdAdminScriptObj.stripePKey);
    var elements = stripe.elements();
    var wpsdDonateAmount = 0;

    var style = {
        base: {
            color: "#32325d",
        }
    };

    var card = elements.create('card', {
        hidePostalCode: true,
        style: style,
    });

    card.mount("#card-element");

    card.addEventListener('change', ({ error }) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    form.addEventListener('submit', function(e) {

        e.preventDefault();
        var wpsdShowCheckout = true;
        if (($("#wpsd_donation_for").val() == '') || ($("#wpsd_donation_for").val() == null)) {
            $('#card-errors').show('slow').addClass('error').html('Please Enter Donation For');
            $("#wpsd_donation_for").focus();
            return false;
        }

        if ($("#wpsd_donator_name").val() == '') {
            $('#card-errors').show('slow').addClass('error').html('Please Enter Name');
            $("#wpsd_donator_name").focus();
            return false;
        }

        if ($("#wpsd_donator_email").val() == '') {
            $('#card-errors').show('slow').addClass('error').html('Please Enter Email');
            $("#wpsd_donator_email").focus();
            return false;
        }

        if (!wpsd_validate_email($("#wpsd_donator_email").val())) {
            $('#card-errors').show('slow').addClass('error').html('Please Enter Valid Email');
            $("#wpsd_donator_email").focus();
            return false;
        }

        if ($("#wpsd_donate_other_amount").val() != '') {
            wpsdDonateAmount = $("#wpsd_donate_other_amount").val();
        } else {
            var wpsdRadioVal = $(".wpsd-wrapper-content #wpsd_donate_amount input[name='wpsd_donate_amount']:checked").val();
            if (wpsdRadioVal !== undefined) {
                wpsdDonateAmount = wpsdRadioVal;
            } else {
                wpsdShowCheckout = false;
                $('#card-errors').show('slow').addClass('error').html('Amount Missing');
            }
        }

        if (wpsdAdminScriptObj.stripePKey == '') {
            $('#card-errors').show('slow').addClass('error').html('Private key missing!');
            return false;
        }

        if (wpsdAdminScriptObj.stripeSKey == '') {
            $('#card-errors').show('slow').addClass('error').html('Secret key missing!');
            return false;
        }

        if (wpsdShowCheckout) {

            $.ajax({
                url: wpsdAdminScriptObj.ajaxurl,
                type: "POST",
                dataType: "JSON",
                data: {
                    action: 'wpsd_donation',
                    wpsdSecretKey: wpsdAdminScriptObj.stripeSKey,
                    email: $("#wpsd_donator_email").val(),
                    amount: wpsdDonateAmount,
                    donation_for: $("#wpsd_donation_for").val(),
                    name: $("#wpsd_donator_name").val(),
                    currency: wpsdAdminScriptObj.currency,
                    idempotency: wpsdAdminScriptObj.idempotency
                },
                success: function(response) {
                    if (response.status === 'success') {
                        stripe.confirmCardPayment(response.client_secret, {
                            payment_method: {
                                card: card,
                                billing_details: {
                                    name: $("#wpsd_donator_name").val(),
                                    email: $("#wpsd_donator_email").val(),
                                }
                            }
                        }).then(function(result) {

                            if (result.error) {

                                $('#card-errors').text(result.error.message);

                            } else {

                                if (result.paymentIntent.status === 'succeeded') {

                                    afterPaymentSucceeded($("#wpsd_donator_email").val(), wpsdDonateAmount, $("#wpsd_donation_for").val(), $("#wpsd_donator_name").val(), wpsdAdminScriptObj.currency);
                                }
                            }
                        });
                    }
                    if (response.status === 'error') {
                        $('#card-errors').show('slow').removeClass('success').addClass(response.status).html(response.message);
                    }
                }
            });
        }
    });

    $("#wpsd-donation-form-id input[type='radio']").on("click", function() {

        $('#wpsd_donate_other_amount').val('');

    });

    $('#wpsd_donate_other_amount').on('keyup', function(e) {

        $("#wpsd-donation-form-id input[type='radio']").prop("checked", false);

        if (/^(\d+(\.\d{0,2})?)?$/.test($(this).val())) {
            $(this).data('prevValue', $(this).val());
        } else {
            $(this).val($(this).data('prevValue') || '');
        }
    });

    function wpsd_validate_email($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    }

    function afterPaymentSucceeded(email, amount, donateFor, name, currency) {
        $.ajax({
            url: wpsdAdminScriptObj.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
                action: 'wpsd_donation_success',
                email: email,
                amount: amount,
                donation_for: donateFor,
                name: name,
                currency: currency
            },
            success: function(response) {
                if (response.status === 'success') {
                    var url = new URL(wpsdAdminScriptObj.successUrl);
                    url.searchParams.set('donation', 'success');
                    window.location.href = url.href;
                }
                if (response.status === 'error') {
                    $('#card-errors').show('slow').removeClass('success').addClass(response.status).html(response.message);
                }
            }
        });
    }

})(window, jQuery);