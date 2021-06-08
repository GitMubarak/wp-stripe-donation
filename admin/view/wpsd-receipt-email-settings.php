<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$wpsd_re_email_subject  = isset( $wpsdEmailSettings['wpsd_re_email_subject'] ) ? $wpsdEmailSettings['wpsd_re_email_subject'] : '';
$wpsd_re_email_heading  = isset( $wpsdEmailSettings['wpsd_re_email_heading'] ) ? $wpsdEmailSettings['wpsd_re_email_heading'] : '';
$wpsd_re_email_footnote	= isset( $wpsdEmailSettings['wpsd_re_email_footnote'] ) ? $wpsdEmailSettings['wpsd_re_email_footnote'] : '';
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
                            <br>
                            <code><?php _e('We received your payment!', WPSD_TXT_DOMAIN); ?></code>
                        </td>
                    </tr>
                    <tr class="wpsd_re_email_heading">
                        <th scope="row">
                            <label
                                for="wpsd_re_email_heading"><?php _e('Heading:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_re_email_heading" id="wpsd_re_email_heading" class="regular-text"
                                value="<?php esc_attr_e( $wpsd_re_email_heading ); ?>" />
                            <br>
                            <code><?php _e('Thanks for your payment!', WPSD_TXT_DOMAIN); ?></code>
                        </td>
                    </tr>
                    <tr class="wpsd_re_email_footnote">
                        <th scope="row">
                            <label
                                for="wpsd_re_email_footnote"><?php _e('Footnote:', WPSD_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wpsd_re_email_footnote" id="wpsd_re_email_footnote" class="regular-text"
                                value="<?php esc_attr_e( $wpsd_re_email_footnote ); ?>" />
                            <br>
                            <code><?php _e('If you have any question, please reply this email.', WPSD_TXT_DOMAIN); ?></code>
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