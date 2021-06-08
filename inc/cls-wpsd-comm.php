<?php
if ( ! defined( 'ABSPATH' ) ) exit;

trait Wpsd_Common {

    protected function get_receipt_email_settings() {
		return stripslashes_deep( unserialize( get_option('wpsd_receipt_email_settings') ) );
	}

    protected function post_receipt_email_settings( $post ) {

        $wpsdEmailSettings = array(
            'wpsd_re_email_subject' => isset( $post['wpsd_re_email_subject'] ) ? sanitize_text_field( $post['wpsd_re_email_subject'] ) : '',
            'wpsd_re_email_heading' => isset( $post['wpsd_re_email_heading'] ) ? sanitize_text_field( $post['wpsd_re_email_heading'] ) : '',
            'wpsd_re_email_footnote' => isset( $post['wpsd_re_email_footnote'] ) ? sanitize_text_field( $post['wpsd_re_email_footnote'] ) : '',
        );
    
        return update_option( 'wpsd_receipt_email_settings', serialize( $wpsdEmailSettings ) );
    }

	protected function wpsd_admin_sidebar() {
		?>
		<div class="wpsd-admin-sidebar" style="width: 277px; float: left; margin-top: 5px;">
			<div class="postbox">
				<h3 class="hndle"><span>Support / Bug / Customization</span></h3>
				<div class="inside centered">
					<p>Please feel free to let us know if you have any bugs to report. Your report / suggestion can make the plugin awesome!</p>
					<p style="margin-bottom: 1px! important;"><a href="https://hmplugin.com/contact/" target="_blank" class="button button-primary">Get Support</a></p>
				</div>
			</div>
			<div class="postbox">
				<h3 class="hndle"><span>Buy us a coffee</span></h3>
				<div class="inside centered">
					<p>If you like the plugin, would you like to support the advancement of this plugin?</p>
					<p style="margin-bottom: 1px! important;"><a href='https://www.paypal.me/mhmrajib' class="button button-primary" target="_blank">Donate</a></p>
				</div>
			</div>

			<div class="postbox">
				<h3 class="hndle"><span>Join HM Plugin on facebook</span></h3>
				<div class="inside centered">
					<iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/hmplugin&amp;width&amp;height=258&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:220px;" allowTransparency="true"></iframe>
				</div>
			</div>

			<div class="postbox">
				<h3 class="hndle"><span>Follow HM Plugin on twitter</span></h3>
				<div class="inside centered">
					<a href="https://twitter.com/hmplugin" target="_blank" class="button button-secondary">Follow @hmplugin<span class="dashicons dashicons-twitter" style="position: relative; top: 3px; margin-left: 3px; color: #0fb9da;"></span></a>
				</div>
			</div>
		</div> 
		<?php
	}
}