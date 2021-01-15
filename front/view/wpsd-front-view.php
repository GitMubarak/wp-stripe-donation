<?php
$wpsdGeneralSettings = stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );
if (is_array($wpsdGeneralSettings)) {
    $wpsdDonationEmail = $wpsdGeneralSettings['wpsd_donation_email'];
    $wpsdPaymentTitle = $wpsdGeneralSettings['wpsd_payment_title'];
    $wpsdDonationOptions = $wpsdGeneralSettings['wpsd_donation_options'];
    $wpsdDonateButtonText = $wpsdGeneralSettings['wpsd_donate_button_text'];
    $wpsdDonateCurrency = $wpsdGeneralSettings['wpsd_donate_currency'];
} else {
    $wpsdDonationEmail = "";
    $wpsdPaymentTitle = "Donate Us";
    $wpsdDonationOptions = "";
    $wpsdDonateButtonText = "Donate Now";
    $wpsdDonateCurrency = "USD";
}

$wpsd_donation_values   = isset( $wpsdGeneralSettings['wpsd_donation_values'] ) ? explode( ',', $wpsdGeneralSettings['wpsd_donation_values'] ) : array();

//
$wpsdTempSettings = stripslashes_deep( unserialize( get_option('wpsd_temp_settings') ) );

if (is_array($wpsdTempSettings)) {
    $wpsdFormBanner = $wpsdTempSettings['wpsd_form_banner'];
    $wpsdSelectTemp = $wpsdTempSettings['wpsd_select_template'];
} else {
    $wpsdFormBanner = "";
    $wpsdSelectTemp = 0;
}

$wpsd_display_banner        = isset( $wpsdTempSettings['wpsd_display_banner'] ) ? $wpsdTempSettings['wpsd_display_banner'] : '';
$wpsd_display_header        = isset( $wpsdTempSettings['wpsd_display_header'] ) ? $wpsdTempSettings['wpsd_display_header'] : '';
$wpsd_donation_for_label    = isset( $wpsdTempSettings['wpsd_donation_for_label'] ) ? $wpsdTempSettings['wpsd_donation_for_label'] : 'Donation For';
$wpsd_donator_name_label    = isset( $wpsdTempSettings['wpsd_donator_name_label'] ) ? $wpsdTempSettings['wpsd_donator_name_label'] : 'Donator Name';
$wpsd_donator_email_label   = isset( $wpsdTempSettings['wpsd_donator_email_label'] ) ? $wpsdTempSettings['wpsd_donator_email_label'] : 'Donator Email';
$wpsd_donator_phone_label   = isset( $wpsdTempSettings['wpsd_donator_phone_label'] ) ? $wpsdTempSettings['wpsd_donator_phone_label'] : 'Donator Phone';
$wpsd_donate_amount_label   = isset( $wpsdTempSettings['wpsd_donate_amount_label'] ) ? $wpsdTempSettings['wpsd_donate_amount_label'] : 'Donate Amount';

$wpsdDonOpVals = ($wpsdDonationOptions != '') ? explode(',', $wpsdDonationOptions) : array();
?>
<div class="wpsd-master-wrapper wpsd-template-<?php printf('%d', $wpsdSelectTemp); ?>" id="wpsd-wrap-all">
    <?php if( '1' === $wpsd_display_header ) { ?>
        <div class="wpsd-wrapper-header">
            <h2><?php esc_html_e('WP Stripe Donation', WPSD_TXT_DOMAIN); ?></h2>
        </div>
    <?php } ?>
    <?php
    if( '1' === $wpsd_display_banner ) {
        if( intval( $wpsdFormBanner ) > 0 ) {
            echo wp_get_attachment_image( $wpsdFormBanner, 'full', false, array('class' => 'wpsd-form-banner') );
        }
    }
    ?>
    <div class="wpsd-wrapper-content">
        <fieldset id="el##" style="margin:0; padding:0; border:0; border-top:1px solid #CCC;">
            <legend style="padding:0 20px; margin:0 auto;"><?php echo esc_html( $wpsdPaymentTitle ); ?></legend>
        </fieldset>
        <form action="" method="POST" id="wpsd-donation-form-id">
            <!-- Input section -->
            <label for="wpsd_donation_for" class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donation_for_label ); ?>:</label>
            <select name="wpsd_donation_for" id="wpsd_donation_for" class="wpsd-text-field">
                <option value="">-- Select One --</option>
                <?php
                if( count( $wpsdDonOpVals ) > 0 ) {
                    foreach( $wpsdDonOpVals as $wpsdDonOpVal ) {
                    ?>
                    <option value="<?php echo esc_attr( trim( $wpsdDonOpVal ) ); ?>"><?php echo esc_html( trim( $wpsdDonOpVal ) ); ?></option>
                    <?php 
                    }
                } 
                ?>
            </select>
            <!-- Input section -->
            <label for="wpsd_donator_name"
                class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_name_label ); ?>:</label>
            <input type="text" name="wpsd_donator_name" id="wpsd_donator_name" class="wpsd-text-field"
                placeholder="<?php echo esc_attr( $wpsd_donator_name_label ); ?>">
            <!-- Input section -->
            <label for="wpsd_donator_email"
                class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_email_label ); ?>:</label>
            <input type="email" name="wpsd_donator_email" id="wpsd_donator_email" class="wpsd-text-field"
                placeholder="<?php echo esc_attr( $wpsd_donator_email_label ); ?>">
            <!-- Input section -->
            <label for="wpsd_donator_phone"
                class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_phone_label ); ?>:</label>
            <input type="number" name="wpsd_donator_phone" id="wpsd_donator_phone" class="wpsd-text-field"
                placeholder="<?php echo esc_attr( $wpsd_donator_phone_label ); ?>">
            <!-- Input section -->
            <label for="wpsd_donate_amount"
                class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donate_amount_label ); ?>:</label>
            <ul id="wpsd_donate_amount">
                <?php
                if( count( $wpsd_donation_values ) > 0 ) {
                    foreach( $wpsd_donation_values as $wpsdDonationVal ) {
                        if( '' !== $wpsdDonationVal ) {
                    ?>
                    <li>
                        <div class="form-group">
                            <input type="radio" id="amount_<?php echo esc_attr( trim( $wpsdDonationVal ) ); ?>" name="wpsd_donate_amount"
                                value="<?php echo esc_attr( trim( $wpsdDonationVal ) ); ?>">
                            <label for="amount_<?php echo esc_attr( trim( $wpsdDonationVal ) ); ?>"><?php echo esc_html( trim( $wpsdDonationVal ) ); ?> <?php echo esc_html($wpsdDonateCurrency); ?></label>
                        </div>
                    </li>
                    <?php
                        } 
                    }
                } 
                ?>
                <li>
                    <div class="form-group">
                        / Other Amount:
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input id="wpsd_donate_other_amount" type="number" class="wpsd_donate_other_amount"
                            name="wpsd_donate_other_amount"> <?php echo esc_html($wpsdDonateCurrency); ?>
                    </div>
                </li>
            </ul>
            <input type="submit" name="wpsd-donate-button" class="wpsd-donate-button"
                value="<?php echo esc_attr($wpsdDonateButtonText); ?>">
        </form>

        <!-- p class="wpsd-total-donation-today">
            Total&nbsp;<span id="wpsd-total-donation-number">0
                <?php //echo esc_html($wpsdDonateCurrency); 
                ?></span>&nbsp;Donation Today
        </p -->
        <span id="wpsd-donation-message" class="wpsd-alert">&nbsp;</span>
    </div>
</div>