(function(window, $) {

    // USE STRICT
    "use strict";

    var wpsdDonateAmount = 0;

    $('#wpsd_donate_other_amount').on('keyup', function(e) {

        $("#wpsd-donation-form-id input[type='radio']").prop("checked", false);

        if (/^(\d+(\.\d{0,2})?)?$/.test($(this).val())) {
            // Input is OK. Remember this value
            $(this).data('prevValue', $(this).val());
        } else {
            // Input is not OK. Restore previous value
            $(this).val($(this).data('prevValue') || '');
        }
    }); //.trigger('input'); // Initialise the `prevValue` data properties


    if (typeof(StripeCheckout) !== "undefined") {

        $("#wpsd-donation-form-id input[type='radio']").on("click", function() {

            $('#wpsd_donate_other_amount').val('');

        });
        /*
        $('#wpsd_donate_other_amount').on('keyup', function() {

            $("#wpsd-donation-form-id input[type='radio']").prop("checked", false);
            wpsdDonateAmount = $(this).val();
            alert(wpsdDonateAmount);
            //this.value = this.value.replace(/[^0-9\.]/g, '');
            //this.value = this.value.replace(/^[0-9]+\.?[0-9]*$/g, '');
            if (!wpsd_validate_other_amt(this.value)) {
                alert('not match');
            }

        });
        */
        var wpsdHandler = StripeCheckout.configure({
            key: wpsdAdminScriptObj.stripePKey,
            image: wpsdAdminScriptObj.image,
            currency: wpsdAdminScriptObj.currency,
            token: function(token) {

                $.ajax({
                    url: wpsdAdminScriptObj.ajaxurl,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'wpsd_donation',
                        token: token.id,
                        wpsdSecretKey: wpsdAdminScriptObj.stripeSKey,
                        email: token.email,
                        amount: wpsdDonateAmount,
                        donation_for: $("#wpsd_donation_for").val(),
                        name: $("#wpsd_donator_name").val(),
                        phone: $("#wpsd_donator_phone").val(),
                        currency: wpsdAdminScriptObj.currency,
                    },
                    success: function(response) {
                        $('#wpsd-donation-message').show('slow').addClass(response.status).html(response.message);
                    }
                });

            }
        });

        $('.wpsd-donate-button').on('click', function(e) {
            var wpsdShowCheckout = true;
            if ($("#wpsd_donation_for").val() == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Please Enter Donation For');
                $("#wpsd_donation_for").focus();
                return false;
            }
            if ($("#wpsd_donator_name").val() == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Please Enter Donator Name');
                $("#wpsd_donator_name").focus();
                return false;
            }
            if ($("#wpsd_donator_email").val() == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Please Enter Donator Email');
                $("#wpsd_donator_email").focus();
                return false;
            }
            if (!wpsd_validate_email($("#wpsd_donator_email").val())) {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Please Enter Valid Email');
                $("#wpsd_donator_email").focus();
                return false;
            }
            /*
            if ($("#wpsd_donator_phone").val() == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Please Enter Donator Phone');
                $("#wpsd_donator_phone").focus();
                return false;
            }
            */
            if ($("#wpsd_donate_other_amount").val() != '') {
                wpsdDonateAmount = $("#wpsd_donate_other_amount").val();
                //alert(wpsdDonateAmount);
            } else {
                var wpsdRadioVal = $(".wpsd-wrapper-content #wpsd_donate_amount input[name='wpsd_donate_amount']:checked").val();
                if (wpsdRadioVal !== undefined) {
                    wpsdDonateAmount = wpsdRadioVal;
                } else {
                    wpsdShowCheckout = false;
                    //alert("Please select an amount to donate.");
                    $('#wpsd-donation-message').show('slow').addClass('error').html('Please select an amount to donate');
                    //$("#wpsd_donate_other_amount").focus();
                }
            }
            if (wpsdAdminScriptObj.stripePKey == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Private key missing!');
                return false;
            }
            if (wpsdAdminScriptObj.stripeSKey == "") {
                $('#wpsd-donation-message').show('slow').addClass('error').html('Secret key missing!');
                return false;
            }

            if (wpsdShowCheckout) {
                // Open Checkout popup
                wpsdHandler.open({
                    name: wpsdAdminScriptObj.title,
                    description: 'Donation for ' + $("#wpsd_donation_for").val(),
                    amount: wpsdDonateAmount,
                    email: $("#wpsd_donator_email").val(),
                });
            }
            e.preventDefault();
        });

        // Close Checkout on page navigation
        $(window).on('popstate', function() {
            wpsdHandler.close();
        });
    }

    function wpsd_validate_email($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    }

    function wpsd_validate_other_amt(s) {
        var rgx = /^[0-9]*\.?[0-9]*$/;
        return s.match(rgx);
    }

})(window, jQuery);