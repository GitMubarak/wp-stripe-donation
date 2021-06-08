<?php
$wpsdEmailShowMessage = false;

if ( isset( $_POST['updateEmailSettings'] ) ) {

    $wpsdEmailSettingsInfo = array(
        'wpsd_re_email_subject'        => isset( $_POST['wpsd_re_email_subject'] ) ? sanitize_text_field( $_POST['wpsd_re_email_subject'] ) : '',
        //'wpsd_donation_options'     => isset( $_POST['wpsd_donation_options'] ) ? sanitize_textarea_field( $_POST['wpsd_donation_options'] ) : '',
    );

    $wpsdEmailShowMessage = update_option( 'wpsd_receipt_email_settings', serialize( $wpsdEmailSettingsInfo ) );
}

$wpsdEmailSettings      = $this->get_receipt_email_settings();
$wpsd_re_email_subject  = array_key_exists( 'wpsd_re_email_subject', $wpsdEmailSettings ) ? $wpsdEmailSettings['wpsd_re_email_subject'] : '';
?>
<div id="wpsd-wrap-all" class="wrap wpsd-email-settings">

    <div class="settings-banner">
        <h2><?php esc_html_e('Receipt Email Settings', WPSD_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
        if ( $wpsdEmailShowMessage ) {
            $this->wpsd_display_notification('success', 'Your information updated successfully.');
        }
    ?>

    <div class="wpsd-wrap">

        <div class="wpsd_personal_wrap wpsd_personal_help" style="width: 845px; float: left; margin-top: 5px;">

            <form name="wpsd-email-settings-form" role="form" class="form-horizontal" method="post" action="" id="wpsd-settings-form-id">
                <table class="form-table">
                    <tr class="wpsd_re_email_subject">
                        <th scope="row">
                            <label
                                for="wpsd_re_email_subject"><?php _e('Subject:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_re_email_subject" id="wpsd_re_email_subject" class="regular-text"
                                value="<?php esc_attr_e( $wpsd_re_email_subject ); ?>" />
                        </td>
                    </tr>
                </table>
                <p class="submit"><button id="updateEmailSettings" name="updateEmailSettings"
                        class="button button-primary"><?php esc_attr_e('Save Settings', WPSD_TXT_DOMAIN); ?></button>
                </p>
            </form>

        </div>

        <?php $this->wpsd_admin_sidebar(); ?>

    </div>
</div>