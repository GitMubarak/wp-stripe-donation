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
        'wpsd_display_phone'        => isset( $_POST['wpsd_display_phone'] ) && filter_var( $_POST['wpsd_display_phone'], FILTER_SANITIZE_NUMBER_INT ) ? $_POST['wpsd_display_phone'] : '',
        'wpsd_other_amount_text'    => ( sanitize_text_field( $_POST['wpsd_other_amount_text'] ) != '' ) ? sanitize_text_field( $_POST['wpsd_other_amount_text'] ) : '',
    );

    $wpsdTempShowMessage = update_option('wpsd_temp_settings', serialize( $wpsdTempSettingsInfo ) );
}

$wpsdTempSettings           = stripslashes_deep( unserialize( get_option('wpsd_temp_settings') ) );
$wpsdSelectTemp             = isset( $wpsdTempSettings['wpsd_select_template'] ) ? $wpsdTempSettings['wpsd_select_template'] : 0;
$wpsdFormBanner             = isset( $wpsdTempSettings['wpsd_form_banner'] ) ? $wpsdTempSettings['wpsd_form_banner'] : '';
$wpsd_display_banner        = isset( $wpsdTempSettings['wpsd_display_banner'] ) ? $wpsdTempSettings['wpsd_display_banner'] : '';
$wpsd_display_header        = isset( $wpsdTempSettings['wpsd_display_header'] ) ? $wpsdTempSettings['wpsd_display_header'] : '';
$wpsd_donation_for_label    = isset( $wpsdTempSettings['wpsd_donation_for_label'] ) ? $wpsdTempSettings['wpsd_donation_for_label'] : 'Donation For';
$wpsd_donator_name_label    = isset( $wpsdTempSettings['wpsd_donator_name_label'] ) ? $wpsdTempSettings['wpsd_donator_name_label'] : 'Donator Name';
$wpsd_donator_email_label   = isset( $wpsdTempSettings['wpsd_donator_email_label'] ) ? $wpsdTempSettings['wpsd_donator_email_label'] : 'Donator Email';
$wpsd_donator_phone_label   = isset( $wpsdTempSettings['wpsd_donator_phone_label'] ) ? $wpsdTempSettings['wpsd_donator_phone_label'] : 'Donator Phone';
$wpsd_donate_amount_label   = isset( $wpsdTempSettings['wpsd_donate_amount_label'] ) ? $wpsdTempSettings['wpsd_donate_amount_label'] : 'Donate Amount';
$wpsd_display_phone         = isset( $wpsdTempSettings['wpsd_display_phone'] ) ? $wpsdTempSettings['wpsd_display_phone'] : '';
$wpsd_other_amount_text     = isset( $wpsdTempSettings['wpsd_other_amount_text'] ) ? $wpsdTempSettings['wpsd_other_amount_text'] : '';
?>
<div id="wpsd-wrap-all" class="wrap wpsd-template-settings">

    <div class="settings-banner">
        <h2><?php _e('Template Settings', WPSD_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
        if ( $wpsdTempShowMessage ) { 
            $this->wpsd_display_notification('success', 'Your information updated successfully.'); 
        } 
    ?>

    <div class="wpsd-wrap">

        <div class="wpsd_personal_wrap wpsd_personal_help" style="width: 845px; float: left; margin-top: 5px;">
            
            <form name="wpsd-temp-settings-form" role="form" class="form-horizontal" method="post" action=""
                id="wpsd-temp-settings-form-id">
                <table class="form-table">
                    <tr class="wpsd_select_template">
                        <th scope="row">
                            <label
                                for="wpsd_select_template"><?php _e('Select a Template:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <div class="wpsd-template-selector">
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                <div class="wpsd-template-item">
                                    <input type="radio" name="wpsd_select_template"
                                        id="<?php printf('wpsd_select_template_%d', $i); ?>" value="<?php printf('%d', $i); ?>"
                                        <?php if ( $wpsdSelectTemp == $i ) echo 'checked'; ?>>
                                    <label class="wpsd-template-<?php printf('%d', $i); ?>"></label>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </td>
                    </tr>
                    <tr class="wpsd_display_banner">
                        <th scope="row">
                            <label for="wpsd_display_banner"><?php _e('Display Banner:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="wpsd_display_banner" class="wpsd_display_banner" id="wpsd_display_banner" value="1" <?php if( '1' === $wpsd_display_banner ) { echo 'checked'; } ?> >
                        </td>
                    </tr>
                    <tr class="wpsd_form_banner">
                        <th scope="row">
                            <label for="wpsd_form_banner"><?php _e('Donation Form Banner:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="hidden" name="wpsd_form_banner" id="wpsd_form_banner"
                                value="<?php echo esc_attr($wpsdFormBanner); ?>" class="regular-text" />
                            <input type='button' class="button-primary"
                                value="<?php esc_attr_e('Select a banner', WPSD_TXT_DOMAIN); ?>" id="wpsd_media_manager"
                                data-image-type="full" />
                            <?php
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
                            <label for="wpsd_display_header"><?php _e('Display Header:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="wpsd_display_header" class="wpsd_display_header" id="wpsd_display_header" value="1" <?php if( '1' === $wpsd_display_header ) { echo 'checked'; } ?> >
                        </td>
                    </tr>
                    <tr class="wpsd_donation_for_label">
                        <th scope="row">
                            <label for="wpsd_donation_for_label"><?php _e('Donation For Label:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donation_for_label" class="medium-text" placeholder="Donation For"
                                value="<?php echo esc_attr( $wpsd_donation_for_label ); ?>">
                        </td>
                    </tr>
                    <tr class="wpsd_donator_name_label">
                        <th scope="row">
                            <label for="wpsd_donator_name_label"><?php _e('Donator Name Label:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donator_name_label" class="medium-text" placeholder="Donator Name"
                                value="<?php echo esc_attr( $wpsd_donator_name_label ); ?>">
                        </td>
                    </tr>
                    <tr class="wpsd_donator_email_label">
                        <th scope="row">
                            <label for="wpsd_donator_email_label"><?php _e('Donator Email Label:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donator_email_label" class="medium-text" placeholder="Donator Email"
                                value="<?php echo esc_attr( $wpsd_donator_email_label ); ?>">
                        </td>
                    </tr>
                    <tr class="wpsd_display_phone">
                        <th scope="row">
                            <label for="wpsd_display_phone"><?php _e('Display Phone:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="wpsd_display_phone" class="wpsd_display_phone" id="wpsd_display_phone" value="1" <?php echo $wpsd_display_phone ? 'checked' : ''; ?> >
                        </td>
                    </tr>
                    <tr class="wpsd_donator_phone_label">
                        <th scope="row">
                            <label for="wpsd_donator_phone_label"><?php _e('Donator Phone Label:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donator_phone_label" class="medium-text" placeholder="Donator Phone"
                                value="<?php echo esc_attr( $wpsd_donator_phone_label ); ?>">
                        </td>
                    </tr>
                    <tr class="wpsd_donate_amount_label">
                        <th scope="row">
                            <label for="wpsd_donate_amount_label"><?php _e('Donate Amount Label:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_donate_amount_label" class="medium-text" placeholder="Donate Amount"
                                value="<?php echo esc_attr( $wpsd_donate_amount_label ); ?>">
                        </td>
                    </tr>
                    <tr class="wpsd_other_amount_text">
                        <th scope="row">
                            <label for="wpsd_other_amount_text"><?php _e('Other Amount Text:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_other_amount_text" class="medium-text" placeholder="<?php esc_attr_e( 'Other Amount', WPSD_TXT_DOMAIN ); ?>"
                                value="<?php echo esc_attr( $wpsd_other_amount_text ); ?>">
                        </td>
                    </tr>
                </table>
                <p class="submit"><button id="updateTempSettings" name="updateTempSettings"
                        class="button button-primary"><?php _e('Save Settings', WPSD_TXT_DOMAIN); ?></button>
                </p>
            </form>
        
        </div>

        <?php $this->wpsd_admin_sidebar(); ?>

    </div>

</div>