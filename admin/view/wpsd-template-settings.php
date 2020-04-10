<?php
$wpsdTempShowMessage = false;
if (isset($_POST['updateTempSettings'])) {
    $wpsdTempSettingsInfo = array(
        'wpsd_select_template' => (filter_var($_POST['wpsd_select_template'], FILTER_SANITIZE_STRING)) ? $_POST['wpsd_select_template'] : 0,
        'wpsd_form_banner' => (sanitize_file_name($_POST['wpsd_form_banner']) != '') ? sanitize_file_name($_POST['wpsd_form_banner']) : ''
    );
    $wpsdTempShowMessage = update_option('wpsd_temp_settings', serialize($wpsdTempSettingsInfo));
}
$wpsdTempSettings = stripslashes_deep(unserialize(get_option('wpsd_temp_settings')));
if (is_array($wpsdTempSettings)) {
    $wpsdSelectTemp = $wpsdTempSettings['wpsd_select_template'];
    $wpsdFormBanner = $wpsdTempSettings['wpsd_form_banner'];
} else {
    $wpsdSelectTemp = 0;
    $wpsdFormBanner = "";
}
?>
<div id="wpsd-wrap-all" class="wrap">
    <div class="settings-banner">
        <h2><?php esc_html_e('WP Stripe Template Settings', WPSD_TXT_DOMAIN); ?></h2>
    </div>
    <?php if ($wpsdTempShowMessage) : $this->wpsd_display_notification('success', 'Your information updated successfully.');
    endif; ?>

    <form name="wpsd-temp-settings-form" role="form" class="form-horizontal" method="post" action=""
        id="wpsd-temp-settings-form-id">
        <table class="form-table">
            <tr class="wpsd_select_template">
                <th scope="row">
                    <label
                        for="wpsd_select_template"><?php esc_html_e('Select a Template:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <div class="wpsd-template-selector">
                        <?php for ($i = 0; $i < 5; $i++) : ?>
                        <div class="wpsd-template-item">
                            <input type="radio" name="wpsd_select_template"
                                id="<?php printf('wpsd_select_template_%d', $i); ?>" value="<?php printf('%d', $i); ?>"
                                <?php if ($wpsdSelectTemp == $i) echo 'checked'; ?>>
                            <label class="wpsd-template-<?php printf('%d', $i); ?>"></label>
                        </div>
                        <?php endfor; ?>
                    </div>
                </td>
            </tr>
            <tr class="wpsd_form_banner">
                <th scope="row">
                    <label for="wpsd_form_banner"><?php esc_html_e('Donation Form Banner:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="hidden" name="wpsd_form_banner" id="wpsd_form_banner"
                        value="<?php echo esc_attr($wpsdFormBanner); ?>" class="regular-text" />
                    <input type='button' class="button-primary"
                        value="<?php esc_attr_e('Select a banner', WPSD_TXT_DOMAIN); ?>" id="wpsd_media_manager"
                        data-image-type="full" />
                    <?php
                    //$wpsdFormBannerImageId = esc_attr($wpsdFormBanner);
                    $wpsdFormBannerImage = "";
                    if (intval($wpsdFormBanner) > 0) {
                        $wpsdFormBannerImage = wp_get_attachment_image($wpsdFormBanner, 'full', false, array('id' => 'wpsd-form-banner-preview-image'));
                    }
                    ?>
                    <div id="wpsd-form-banner-preview-image">
                        <?php echo $wpsdFormBannerImage; ?>
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit"><button id="updateTempSettings" name="updateTempSettings"
                class="button button-primary"><?php esc_attr_e('Update Temp Settings', WPSD_TXT_DOMAIN); ?></button>
        </p>
    </form>
</div>