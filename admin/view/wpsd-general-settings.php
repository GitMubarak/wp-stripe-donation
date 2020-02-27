<?php
$wpsdGeneralShowMessage = false;
if(isset($_POST['updateGeneralSettings'])){
     $wpsdGeneralSettingsInfo = array(
                                'wpsd_donation_email' => (sanitize_email($_POST['wpsd_donation_email'])!='') ? sanitize_email($_POST['wpsd_donation_email']) : '',
                                'wpsd_payment_title' => (sanitize_text_field($_POST['wpsd_payment_title'])!='') ? sanitize_text_field($_POST['wpsd_payment_title']) : '',
                                'wpsd_donate_button_text' => (sanitize_text_field($_POST['wpsd_donate_button_text'])!='') ? sanitize_text_field($_POST['wpsd_donate_button_text']) : '',
                                'wpsd_payment_logo' => (sanitize_file_name($_POST['wpsd_payment_logo'])!='') ? sanitize_file_name($_POST['wpsd_payment_logo']) : ''
                              );
    $wpsdGeneralShowMessage = update_option('wpsd_general_settings', serialize($wpsdGeneralSettingsInfo));  
}
$wpsdGeneralSettings = stripslashes_deep(unserialize(get_option('wpsd_general_settings')));
if(is_array($wpsdGeneralSettings)){
    $wpsdDonationEmail = !empty($wpsdGeneralSettings['wpsd_donation_email']) ? $wpsdGeneralSettings['wpsd_donation_email'] : "";
    $wpsdPaymentTitle = !empty($wpsdGeneralSettings['wpsd_payment_title']) ? $wpsdGeneralSettings['wpsd_payment_title'] : "";
    $wpsdDonateButtonText = !empty($wpsdGeneralSettings['wpsd_donate_button_text']) ? $wpsdGeneralSettings['wpsd_donate_button_text'] : "";
    $wpsdPaymentLogo = !empty($wpsdGeneralSettings['wpsd_payment_logo']) ? $wpsdGeneralSettings['wpsd_payment_logo'] : "";
} else{
    $wpsdDonationEmail = "";
    $wpsdPaymentTitle = "";
    $wpsdDonateButtonText = "";
    $wpsdPaymentLogo = "";
}
?>
<div id="wpsd-wrap-all" class="wrap">
     <div class="settings-banner">
          <h2><?php esc_html_e('WP Stripe General Settings', WPSD_TXT_DOMAIN); ?></h2>
     </div>
     <?php if($wpsdGeneralShowMessage): $this->wpsd_display_notification('success', 'Your information updated successfully.'); endif; ?>

     <form name="wpsd-general-settings-form" role="form" class="form-horizontal" method="post" action="" id="wpsd-settings-form-id">
          <table class="form-table">
          <tr class="wpsd_donation_email">
               <th scope="row">
                    <label for="wpsd_donation_email"><?php esc_html_e('Donation Info Email:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="text" name="wpsd_donation_email" id="wpsd_donation_email" class="regular-text" value="<?php echo esc_attr($wpsdDonationEmail); ?>" />
               </td>
          </tr>
          <tr class="wpsd_payment_title">
               <th scope="row">
                    <label for="wpsd_payment_title"><?php esc_html_e('Stripe Donation Title:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="text" name="wpsd_payment_title" id="wpsd_payment_title" class="regular-text" value="<?php echo esc_attr($wpsdPaymentTitle); ?>" />
               </td>
          </tr>
          <tr class="wpsd_donate_button_text">
               <th scope="row">
                    <label for="wpsd_donate_button_text"><?php esc_html_e('Donate Button Text:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="text" name="wpsd_donate_button_text" id="wpsd_donate_button_text" class="regular-text" value="<?php echo esc_attr($wpsdDonateButtonText); ?>" />
               </td>
          </tr>
          <tr class="wpsd_payment_logo">
               <th scope="row">
                    <label for="wpsd_payment_logo"><?php esc_html_e('Stripe Payment Logo:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="hidden" name="wpsd_payment_logo" id="wpsd_payment_logo" value="<?php echo esc_attr( $wpsdPaymentLogo); ?>" class="regular-text" />
                    <input type='button' class="button-primary" value="<?php esc_attr_e( 'Select a logo', WPSD_TXT_DOMAIN ); ?>" id="wpsd_media_manager"/>
                    <br><br>
                    <?php
                    $wpsdImageId = esc_attr( $wpsdPaymentLogo );
                    $wpsdImage = "";
                    if( intval( $wpsdImageId ) > 0 ) {
                         $wpsdImage = wp_get_attachment_image( $wpsdImageId, 'thumbnail', false, array( 'id' => 'wpsd-preview-image' ) );
                    }
                    ?>
                    <div id="wpsd-preview-image"><?php echo $wpsdImage; ?></div>
               </td>
          </tr>
          <tr class="wpsd_shortcode">
               <th scope="row">
                    <label for="wpsd_shortcode"><?php esc_html_e('Shortcode:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="text" name="wpsd_shortcode" id="wpsd_shortcode" class="regular-text" value="[wp_stripe_donation]" readonly />
               </td>
          </tr>
          </table>
          <p class="submit"><button id="updateGeneralSettings" name="updateGeneralSettings" class="button button-primary"><?php esc_attr_e('Update General Settings', WPSD_TXT_DOMAIN); ?></button></p>
     </form>
</div>