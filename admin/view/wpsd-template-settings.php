<?php
$wpsdTempShowMessage = false;
if( isset( $_POST['updateTempSettings'] ) ) {

    $wpsdTempSettingsInfo = array(
        'wpsd_select_template'      => ( filter_var( $_POST['wpsd_select_template'], FILTER_SANITIZE_STRING ) ) ? $_POST['wpsd_select_template'] : 0,
        'wpsd_display_banner'       => isset( $_POST['wpsd_display_banner'] ) && filter_var( $_POST['wpsd_display_banner'], FILTER_SANITIZE_NUMBER_INT ) ? $_POST['wpsd_display_banner'] : '',
        'wpsd_form_banner'          => ( sanitize_file_name( $_POST['wpsd_form_banner'] ) != '' ) ? sanitize_file_name( $_POST['wpsd_form_banner'] ) : '',
        'wpsd_display_header'       => isset( $_POST['wpsd_display_header'] ) && filter_var( $_POST['wpsd_display_header'], FILTER_SANITIZE_NUMBER_INT ) ? $_POST['wpsd_display_header'] : '',
        'wpsd_donation_for_label'   => ( sanitize_text_field( $_POST['wpsd_donation_for_label'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_donation_for_label'] ) : 'Donation For',
        'wpsd_donator_name_label'   => ( sanitize_text_field( $_POST['wpsd_donator_name_label'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_donator_name_label'] ) : 'Donator Name',
        'wpsd_donator_email_label'  => ( sanitize_text_field( $_POST['wpsd_donator_email_label'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_donator_email_label'] ) : 'Donator Email',
        'wpsd_donator_phone_label'  => ( sanitize_text_field( $_POST['wpsd_donator_phone_label'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_donator_phone_label'] ) : 'Donator Phone',
        'wpsd_donate_amount_label'  => ( sanitize_text_field( $_POST['wpsd_donate_amount_label'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_donate_amount_label'] ) : 'Donate Amount',
    );

    $wpsdTempShowMessage = update_option('wpsd_temp_settings', serialize( $wpsdTempSettingsInfo ) );
}

$wpsdTempSettings = stripslashes_deep( unserialize( get_option('wpsd_temp_settings') ) );

if ( is_array( $wpsdTempSettings ) ) {
    $wpsdSelectTemp = $wpsdTempSettings['wpsd_select_template'];
    $wpsdFormBanner = $wpsdTempSettings['wpsd_form_banner'];
} else {
    $wpsdSelectTemp = 0;
    $wpsdFormBanner = '';
}

$wpsd_display_banner        = isset( $wpsdTempSettings['wpsd_display_banner'] ) ? $wpsdTempSettings['wpsd_display_banner'] : '';
$wpsd_display_header        = isset( $wpsdTempSettings['wpsd_display_header'] ) ? $wpsdTempSettings['wpsd_display_header'] : '';
$wpsd_donation_for_label    = isset( $wpsdTempSettings['wpsd_donation_for_label'] ) ? $wpsdTempSettings['wpsd_donation_for_label'] : 'Donation For';
$wpsd_donator_name_label    = isset( $wpsdTempSettings['wpsd_donator_name_label'] ) ? $wpsdTempSettings['wpsd_donator_name_label'] : 'Donator Name';
$wpsd_donator_email_label   = isset( $wpsdTempSettings['wpsd_donator_email_label'] ) ? $wpsdTempSettings['wpsd_donator_email_label'] : 'Donator Email';
$wpsd_donator_phone_label   = isset( $wpsdTempSettings['wpsd_donator_phone_label'] ) ? $wpsdTempSettings['wpsd_donator_phone_label'] : 'Donator Phone';
$wpsd_donate_amount_label   = isset( $wpsdTempSettings['wpsd_donate_amount_label'] ) ? $wpsdTempSettings['wpsd_donate_amount_label'] : 'Donate Amount';
?>
<div id="wpsd-wrap-all" class="wrap">
    <div class="settings-banner">
        <h2><?php esc_html_e('WP Stripe Template Settings', WPSD_TXT_DOMAIN); ?></h2>
    </div>
    <?php if( $wpsdTempShowMessage ) { $this->wpsd_display_notification('success', 'Your information updated successfully.'); } ?>

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
                                <?php if( $wpsdSelectTemp == $i ) echo 'checked'; ?>>
                            <label class="wpsd-template-<?php printf('%d', $i); ?>"></label>
                        </div>
                        <?php endfor; ?>
                    </div>
                </td>
            </tr>
            <tr class="wpsd_display_banner">
                <th scope="row">
                    <label for="wpsd_display_banner"><?php esc_html_e('Display Banner:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wpsd_display_banner" class="wpsd_display_banner" value="1" <?php if( '1' === $wpsd_display_banner ) { echo 'checked'; } ?> >
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
                    if( intval( $wpsdFormBanner ) > 0 ) {
                        $wpsdFormBannerImage = wp_get_attachment_image( $wpsdFormBanner, 'full', false, array('id' => 'wpsd-form-banner-preview-image') );
                    }
                    ?>
                    <div id="wpsd-form-banner-preview-image">
                        <?php echo $wpsdFormBannerImage; ?>
                    </div>
                </td>
            </tr>
            <tr class="wpsd_display_header">
                <th scope="row">
                    <label for="wpsd_display_header"><?php esc_html_e('Display Header:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wpsd_display_header" class="wpsd_display_header" value="1" <?php if( '1' === $wpsd_display_header ) { echo 'checked'; } ?> >
                </td>
            </tr>
            <tr class="wpsd_donation_for_label">
                <th scope="row">
                    <label for="wpsd_donation_for_label"><?php esc_html_e('Donation For Label:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wpsd_donation_for_label" class="medium-text" placeholder="Donation For"
                        value="<?php echo esc_attr( $wpsd_donation_for_label ); ?>">
                </td>
            </tr>
            <tr class="wpsd_donator_name_label">
                <th scope="row">
                    <label for="wpsd_donator_name_label"><?php esc_html_e('Donator Name Label:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wpsd_donator_name_label" class="medium-text" placeholder="Donator Name"
                        value="<?php echo esc_attr( $wpsd_donator_name_label ); ?>">
                </td>
            </tr>
            <tr class="wpsd_donator_email_label">
                <th scope="row">
                    <label for="wpsd_donator_email_label"><?php esc_html_e('Donator Email Label:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wpsd_donator_email_label" class="medium-text" placeholder="Donator Email"
                        value="<?php echo esc_attr( $wpsd_donator_email_label ); ?>">
                </td>
            </tr>
            <tr class="wpsd_donator_phone_label">
                <th scope="row">
                    <label for="wpsd_donator_phone_label"><?php esc_html_e('Donator Phone Label:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wpsd_donator_phone_label" class="medium-text" placeholder="Donator Phone"
                        value="<?php echo esc_attr( $wpsd_donator_phone_label ); ?>">
                </td>
            </tr>
            <tr class="wpsd_donate_amount_label">
                <th scope="row">
                    <label for="wpsd_donate_amount_label"><?php esc_html_e('Donate Amount Label:', WPSD_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wpsd_donate_amount_label" class="medium-text" placeholder="Donate Amount"
                        value="<?php echo esc_attr( $wpsd_donate_amount_label ); ?>">
                </td>
            </tr>
        </table>
        <p class="submit"><button id="updateTempSettings" name="updateTempSettings"
                class="button button-primary"><?php esc_attr_e('Update Temp Settings', WPSD_TXT_DOMAIN); ?></button>
        </p>
    </form>
</div>