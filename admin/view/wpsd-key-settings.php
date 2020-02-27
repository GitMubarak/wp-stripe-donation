<?php
$wpsdKeyShowMessage = false;

if(isset($_POST['updateKeySettings'])){
    $wpsdKeySettingsInfo = array(
                                'wpsd_private_key' => (!empty($_POST['wpsd_private_key']) && (sanitize_text_field($_POST['wpsd_private_key'])!='')) ? sanitize_text_field($_POST['wpsd_private_key']) : '',
                                'wpsd_secret_key'  => (!empty($_POST['wpsd_secret_key']) && (sanitize_text_field($_POST['wpsd_secret_key'])!='')) ? sanitize_text_field($_POST['wpsd_secret_key']) : ''
                            );
    $wpsdKeyShowMessage = update_option('wpsd_key_settings', serialize($wpsdKeySettingsInfo));  
}
$wpsdKeySettings = stripslashes_deep(unserialize(get_option('wpsd_key_settings')));
if(is_array($wpsdKeySettings)){
    $wpsdPrivateKey = !empty($wpsdKeySettings['wpsd_private_key']) ? $wpsdKeySettings['wpsd_private_key'] : "";
    $wpsdSecretKey = !empty($wpsdKeySettings['wpsd_secret_key']) ? $wpsdKeySettings['wpsd_secret_key'] : "";
} else{
    $wpsdPrivateKey = "";
    $wpsdSecretKey = "";
}
?>
<div id="wpsd-wrap-all" class="wrap">
     <div class="settings-banner">
          <h2><?php esc_html_e('WP Stripe Donation Key Settings', WPSD_TXT_DOMAIN); ?></h2>
     </div>
     <?php if($wpsdKeyShowMessage): $this->wpsd_display_notification('success', 'Your information updated successfully.'); endif; ?>

     <form name="wpsd-general-settings-form" role="form" class="form-horizontal" method="post" action="" id="wpsd-settings-form-id">
          <table class="form-table">
          <tr class="wpsd_private_key">
               <th scope="row">
                    <label for="wpsd_private_key"><?php esc_html_e('Private Key:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
               <input type="text" name="wpsd_private_key" id="wpsd_private_key" class="regular-text" value="<?php echo esc_html($wpsdPrivateKey); ?>" />
                </td>
          </tr>
          <tr class="wpsd_secret_key">
               <th scope="row">
                    <label for="wpsd_secret_key"><?php esc_html_e('Secret Key:', WPSD_TXT_DOMAIN); ?></label>
               </th>
               <td>
               <input type="text" name="wpsd_secret_key" id="wpsd_secret_key" class="regular-text" value="<?php echo esc_html($wpsdSecretKey); ?>" />
                </td>
          </tr>
          </table>
          <p class="submit"><button id="updateKeySettings" name="updateKeySettings" class="button button-primary"><?php esc_attr_e('Update Settings', WPSD_TXT_DOMAIN); ?></button></p>
     </form>
</div>