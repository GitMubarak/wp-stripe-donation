<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$wpsdGeneralSettings = stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );

if ( is_array( $wpsdGeneralSettings ) ) {

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

// ========================
// Template Settings
// ========================
$wpsdTempSettings           = stripslashes_deep( unserialize( get_option('wpsd_temp_settings') ) );
$wpsdFormBanner             = isset( $wpsdTempSettings['wpsd_form_banner'] ) ? $wpsdTempSettings['wpsd_form_banner'] : '';
$wpsdSelectTemp             = isset( $wpsdTempSettings['wpsd_select_template'] ) ? $wpsdTempSettings['wpsd_select_template'] : 0;
$wpsd_display_banner        = isset( $wpsdTempSettings['wpsd_display_banner'] ) ? $wpsdTempSettings['wpsd_display_banner'] : '';
$wpsd_display_header        = isset( $wpsdTempSettings['wpsd_display_header'] ) ? $wpsdTempSettings['wpsd_display_header'] : '';
$wpsd_donation_for_label    = isset( $wpsdTempSettings['wpsd_donation_for_label'] ) ? $wpsdTempSettings['wpsd_donation_for_label'] : 'Donation For';
$wpsd_donator_name_label    = isset( $wpsdTempSettings['wpsd_donator_name_label'] ) ? $wpsdTempSettings['wpsd_donator_name_label'] : 'Donator Name';
$wpsd_donator_email_label   = isset( $wpsdTempSettings['wpsd_donator_email_label'] ) ? $wpsdTempSettings['wpsd_donator_email_label'] : 'Donator Email';
$wpsd_donator_phone_label   = isset( $wpsdTempSettings['wpsd_donator_phone_label'] ) ? $wpsdTempSettings['wpsd_donator_phone_label'] : 'Donator Phone';
$wpsd_donate_amount_label   = isset( $wpsdTempSettings['wpsd_donate_amount_label'] ) ? $wpsdTempSettings['wpsd_donate_amount_label'] : 'Donate Amount';
$wpsd_display_phone         = isset( $wpsdTempSettings['wpsd_display_phone'] ) ? $wpsdTempSettings['wpsd_display_phone'] : '';
$wpsd_other_amount_text     = isset( $wpsdTempSettings['wpsd_other_amount_text'] ) ? $wpsdTempSettings['wpsd_other_amount_text'] : '';
$wpsd_hide_label            = isset( $wpsdTempSettings['wpsd_hide_label'] ) ? $wpsdTempSettings['wpsd_hide_label'] : 1;
$wpsd_form_description      = isset( $wpsdGeneralSettings['wpsd_form_description'] ) ? $wpsdGeneralSettings['wpsd_form_description'] : '';

$wpsdDonOpVals = ( $wpsdDonationOptions != '' ) ? explode(',', $wpsdDonationOptions) : array();
?>
<div class="wpsd-master-wrapper wpsd-template-<?php printf('%d', $wpsdSelectTemp); ?>" id="wpsd-wrap-all">
    
    <h3 class="wpsd-form-title"><?php esc_html_e( $wpsdPaymentTitle ); ?></h3>
    <div class="wpsd-form-title-border"></div>
    <p class="wpsd-form-description"><?php esc_html_e( $wpsd_form_description ); ?></p>
    <?php
    if ( $wpsd_display_banner ) {
        if ( intval( $wpsdFormBanner ) > 0 ) {
            echo wp_get_attachment_image( $wpsdFormBanner, 'full', false, array('class' => 'wpsd-form-banner') );
        }
    }
    ?>

    <div class="wpsd-wrapper-content">

        <form action="" method="POST" id="wpsd-donation-form-id" autocomplete="off">
            
            <?php
            if ( ! $wpsd_hide_label ) {
                ?>
                <label for="wpsd_donation_for" class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donation_for_label ); ?>:</label>
                <?php
            }
            ?>
            <select name="wpsd_donation_for" id="wpsd_donation_for" class="wpsd-text-field">
                <?php
                if ( count( $wpsdDonOpVals ) > 0 ) {
                    foreach ( $wpsdDonOpVals as $wpsdDonOpVal ) {
                    ?>
                    <option value="<?php echo esc_attr( trim( $wpsdDonOpVal ) ); ?>"><?php echo esc_html( trim( $wpsdDonOpVal ) ); ?></option>
                    <?php 
                    }
                } 
                ?>
            </select>
            
            <?php
            if ( ! $wpsd_hide_label ) {
                ?>
                <label for="wpsd_donator_name" class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_name_label ); ?>:</label>
                <?php
            }
            ?>
            <input type="text" name="wpsd_donator_name" id="wpsd_donator_name" class="wpsd-text-field"
                placeholder="<?php echo esc_attr( $wpsd_donator_name_label ); ?>">
            
            <?php
            if ( ! $wpsd_hide_label ) {
                ?>
                <label for="wpsd_donator_email" class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_email_label ); ?>:</label>
                <?php
            }
            ?>
            <input type="email" name="wpsd_donator_email" id="wpsd_donator_email" class="wpsd-text-field" placeholder="<?php echo esc_attr( $wpsd_donator_email_label ); ?>">
            
            <?php if ( $wpsd_display_phone ) { ?>
                <label for="wpsd_donator_phone" class="wpsd-donation-form-label"><?php echo esc_html( $wpsd_donator_phone_label ); ?>:</label>

                <input type="text" name="wpsd_donator_phone" id="wpsd_donator_phone" class="wpsd-text-field"
                    placeholder="<?php esc_attr_e( $wpsd_donator_phone_label ); ?>">
            <?php } ?>
            
            <?php
            if ( ! $wpsd_hide_label ) {
                ?>
                <label for="wpsd_donate_amount" class="wpsd-donation-form-label"><?php esc_html_e( $wpsd_donate_amount_label ); ?>:</label>
                <?php
            }
            ?>

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

                if ( '' !== $wpsd_other_amount_text ) {
                ?>
                <li>
                    <div class="form-group"><?php esc_html_e( $wpsd_other_amount_text ); ?></div>
                </li>
                <?php } ?>
                <li>
                    <div class="form-group">
                        <input id="wpsd_donate_other_amount" type="text" class="wpsd_donate_other_amount"
                            name="wpsd_donate_other_amount" placeholder="<?php _e( 'Amount', WPSD_TXT_DOMAIN ); ?>"> <?php esc_html_e( $wpsdDonateCurrency ); ?>
                    </div>
                </li>
            </ul>

            <!--card--->
            <?php
            if ( ! $wpsd_hide_label ) {
                ?>
                <label class="wpsd-donation-form-label"><?php _e( 'Card Details', WPSD_TXT_DOMAIN ); ?></label>
                <?php
            }
            ?>
            <div id="card-element"></div>
            <div id="card-errors" class="wpsd-alert" role="alert"></div>
            <!---card end-->
            <br>
            <input type="submit" name="wpsd-donate-button" class="wpsd-donate-button"
                value="<?php esc_attr_e( $wpsdDonateButtonText ); ?>">
        </form>

        <!-- p class="wpsd-total-donation-today">
            Total&nbsp;<span id="wpsd-total-donation-number">0
                <?php //echo esc_html($wpsdDonateCurrency); 
                ?></span>&nbsp;Donation Today
        </p -->
    </div>
</div>