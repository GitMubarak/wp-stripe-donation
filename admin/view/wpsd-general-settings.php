<?php
$wpsdGeneralShowMessage = false;

if ( isset( $_POST['updateGeneralSettings'] ) ) {

    $wpsdGeneralSettingsInfo = array(
        'wpsd_donation_email'       => isset( $_POST['wpsd_donation_email'] ) ? sanitize_email( $_POST['wpsd_donation_email'] ) : '',
        'wpsd_payment_title'        => isset( $_POST['wpsd_payment_title'] ) ? sanitize_text_field( $_POST['wpsd_payment_title'] ) : '',
        'wpsd_donation_options'     => isset( $_POST['wpsd_donation_options'] ) ? sanitize_textarea_field( $_POST['wpsd_donation_options'] ) : '',
        'wpsd_donation_values'      => isset( $_POST['wpsd_donation_values'] ) ? sanitize_textarea_field( $_POST['wpsd_donation_values'] ) : '',
        'wpsd_donate_button_text'   => isset( $_POST['wpsd_donate_button_text'] ) ? sanitize_text_field( $_POST['wpsd_donate_button_text'] ) : '',
        'wpsd_donate_currency'      => isset( $_POST['wpsd_donate_currency'] ) ? sanitize_text_field( $_POST['wpsd_donate_currency'] ) : 'USD',
        'wpsd_form_description'     => isset( $_POST['wpsd_form_description'] ) ? sanitize_text_field( $_POST['wpsd_form_description'] ) : '',
    );

    $wpsdGeneralShowMessage = update_option( 'wpsd_general_settings', serialize( $wpsdGeneralSettingsInfo ) );
}

$wpsdGeneralSettings    = stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );
$wpsd_donation_email    = isset( $wpsdGeneralSettings['wpsd_donation_email'] ) ? $wpsdGeneralSettings['wpsd_donation_email'] : '';
$wpsd_payment_title     = isset( $wpsdGeneralSettings['wpsd_payment_title'] ) ? $wpsdGeneralSettings['wpsd_payment_title'] : '';
$wpsdDonationOptions    = isset( $wpsdGeneralSettings['wpsd_donation_options'] ) ? $wpsdGeneralSettings['wpsd_donation_options'] : '';
$wpsdDonateButtonText   = isset( $wpsdGeneralSettings['wpsd_donate_button_text'] ) ? $wpsdGeneralSettings['wpsd_donate_button_text'] : '';
$wpsd_donation_values   = isset( $wpsdGeneralSettings['wpsd_donation_values'] ) ? $wpsdGeneralSettings['wpsd_donation_values'] : '';
$wpsd_currency          = isset( $wpsdGeneralSettings['wpsd_donate_currency'] ) ? $wpsdGeneralSettings['wpsd_donate_currency'] : 'USD';
$wpsd_form_description  = isset( $wpsdGeneralSettings['wpsd_form_description'] ) ? $wpsdGeneralSettings['wpsd_form_description'] : '';
?>
<div id="wpsd-wrap-all" class="wrap wpsd-general-settings">

    <div class="settings-banner">
        <h2><?php _e('General Settings', WPSD_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
        if ( $wpsdGeneralShowMessage ) {
            $this->wpsd_display_notification('success', 'Your information updated successfully.');
        }
    ?>

    <div class="wpsd-wrap">

        <div class="wpsd_personal_wrap wpsd_personal_help" style="width: 845px; float: left; margin-top: 5px;">

            <form name="wpsd-general-settings-form" role="form" class="form-horizontal" method="post" action=""
                id="wpsd-settings-form-id">
                <table class="form-table">
                    <tr class="wpsd_donation_email">
                        <th scope="row">
                            <label
                                for="wpsd_donation_email"><?php _e('Donation Info Email:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donation_email" id="wpsd_donation_email" class="regular-text"
                                value="<?php esc_attr_e( $wpsd_donation_email ); ?>" />
                            <br>
                            <code>A copy of donation information will send to this email.</code>
                        </td>
                    </tr>
                    <tr class="wpsd_payment_title">
                        <th scope="row">
                            <label for="wpsd_payment_title"><?php _e('Form Title:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_payment_title" id="wpsd_payment_title" class="regular-text"
                                value="<?php esc_attr_e( $wpsd_payment_title ); ?>" />
                        </td>
                    </tr>
                    <tr class="wpsd_form_description">
                        <th scope="row">
                            <label for="wpsd_form_description"><?php _e('Form Description:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <textarea cols="40" style="min-height:100px;" name="wpsd_form_description" class="regular-text"
                                id="wpsd_form_description"><?php esc_html_e( $wpsd_form_description ); ?></textarea>
                        </td>
                    </tr>
                    <tr class="wpsd_donation_options">
                        <th scope="row">
                            <label
                                for="wpsd_donation_options"><?php _e('Donation For Options:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <textarea cols="40" style="min-height:100px;" name="wpsd_donation_options" class="regular-text"
                                id="wpsd_donation_options"><?php echo esc_html($wpsdDonationOptions); ?></textarea>
                            <br>
                            <code>Use comma "," separated values like: Option-1, Option-2</code>
                        </td>
                    </tr>
                    <tr class="wpsd_donation_values">
                        <th scope="row">
                            <label
                                for="wpsd_donation_values"><?php _e('Amounts:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <textarea cols="40" style="min-height:100px;" name="wpsd_donation_values" class="regular-text"
                                id="wpsd_donation_values"><?php echo esc_html($wpsd_donation_values); ?></textarea>
                            <br>
                            <code>Use comma "," separated values like: 100,150,200</code>
                        </td>
                    </tr>
                    <tr class="wpsd_donate_button_text">
                        <th scope="row">
                            <label
                                for="wpsd_donate_button_text"><?php _e('Form Button Text:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donate_button_text" id="wpsd_donate_button_text" class="regular-text"
                                value="<?php echo esc_attr($wpsdDonateButtonText); ?>" />
                        </td>
                    </tr>
                    <tr class="wpsd_donate_currency">
                        <th scope="row">
                            <label for="wpsd_donate_currency"><?php _e('Currency:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <select name="wpsd_donate_currency" id="wpsd_donate_currency" class="regular-text">
                                <?php
                                $wpsdCurrency = $this->hm_get_all_currency();
                                foreach ( $wpsdCurrency as $wpsdcurr ) { 
                                    ?>
                                    <option value="<?php echo esc_attr( $wpsdcurr->abbreviation ); ?>" 
                                        <?php if ( $wpsd_currency === $wpsdcurr->abbreviation ) echo 'selected'; ?> >
                                        <?php echo esc_html( $wpsdcurr->currency ); ?>-<?php echo esc_html( $wpsdcurr->abbreviation ); ?>-<?php echo esc_html( $wpsdcurr->symbol ); ?>
                                    </option>
                                    <?php 
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="wpsd_shortcode">
                        <th scope="row">
                            <label for="wpsd_shortcode"><?php esc_html_e('Shortcode:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_shortcode" id="wpsd_shortcode" class="regular-text"
                                value="[wp_stripe_donation]" readonly />
                        </td>
                    </tr>
                </table>
                <p class="submit"><button id="updateGeneralSettings" name="updateGeneralSettings"
                        class="button button-primary"><?php esc_attr_e('Save Settings', WPSD_TXT_DOMAIN); ?></button>
                </p>
            </form>

        </div>

        <?php $this->wpsd_admin_sidebar(); ?>

    </div>
</div>