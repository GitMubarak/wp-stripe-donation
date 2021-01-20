<?php

/**
 *	Admin Panel Parent Class
 */
class Wpsd_Admin
{
	use HM_Currency;

	private $wpsd_version;
	private $wpsd_option_group;
	private $wpsd_assets_prefix;
	protected $wpsdTable;

	public function __construct($version)
	{
		$this->wpsd_version = $version;
		$this->wpsdTable = WPSD_TABLE;
		$this->wpsd_option_group = WPSD_PRFX . 'options_group';
		$this->wpsd_assets_prefix = substr(WPSD_PRFX, 0, -1) . '-';
	}

	/**
	 *	Loading the admin menu
	 */
	public function wpsd_admin_menu()
	{
		add_menu_page(
			__('WP Stripe Donation', WPSD_TXT_DOMAIN),
			__('WP Stripe Donation', WPSD_TXT_DOMAIN),
			'',
			'wpsd-admin-settings',
			'',
			'dashicons-money-alt',
			100
		);

		add_submenu_page(
			'wpsd-admin-settings',
			esc_html__('Key Settings', WPSD_TXT_DOMAIN),
			esc_html__('Key Settings', WPSD_TXT_DOMAIN),
			'manage_options',
			'wpsd-key-settings',
			array($this, WPSD_PRFX . 'key_settings')
		);

		add_submenu_page(
			'wpsd-admin-settings',
			esc_html__('General Settings', WPSD_TXT_DOMAIN),
			esc_html__('General Settings', WPSD_TXT_DOMAIN),
			'manage_options',
			'wpsd-general-settings',
			array($this, WPSD_PRFX . 'general_settings')
		);

		add_submenu_page(
			'wpsd-admin-settings',
			esc_html__('Template Settings', WPSD_TXT_DOMAIN),
			esc_html__('Template Settings', WPSD_TXT_DOMAIN),
			'manage_options',
			'wpsd-template-settings',
			array($this, WPSD_PRFX . 'template_settings')
		);

		add_submenu_page(
			'wpsd-admin-settings',
			__('Donations Info', WPSD_TXT_DOMAIN),
			__('Donations Info', WPSD_TXT_DOMAIN),
			'manage_options',
			'wpsd-all-donations',
			array($this, WPSD_PRFX . 'all_donations')
		);

		add_submenu_page(
			'wpsd-admin-settings',
			__('Help & Usage', WPSD_TXT_DOMAIN),
			__('Help & Usage', WPSD_TXT_DOMAIN),
			'manage_options',
			'wpsd-get-help',
			array( $this, WPSD_PRFX . 'get_help' )
		);
	}

	/**
	 *	Loading admin panel assets
	 */
	function wpsd_admin_assets()
	{
		wp_enqueue_style(
			$this->wpsd_assets_prefix . 'admin-style',
			WPSD_ASSETS . 'css/' . $this->wpsd_assets_prefix . 'admin-style.css',
			array(),
			$this->wpsd_version,
			FALSE
		);

		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');

		wp_enqueue_media();

		if (!wp_script_is('jquery')) {
			wp_enqueue_script('jquery');
		}
		wp_enqueue_script(
			$this->wpsd_assets_prefix . 'admin-script',
			WPSD_ASSETS . 'js/' . $this->wpsd_assets_prefix . 'admin-script.js',
			array('jquery'),
			$this->wpsd_version,
			TRUE
		);
		$wpsd_settings = get_option('wpsd_settings');
		$wpsdAdminArray = array(
			'wpsdIdsOfColorPicker' => array()
		);
		wp_localize_script($this->wpsd_assets_prefix . 'admin-script', 'wpsdAdminScript', $wpsdAdminArray);
	}

	/**
	 *	Loading admin panel view/forms
	 */
	function wpsd_key_settings()
	{
		require_once WPSD_PATH . 'admin/view/' . $this->wpsd_assets_prefix . 'key-settings.php';
	}

	function wpsd_general_settings()
	{
		require_once WPSD_PATH . 'admin/view/' . $this->wpsd_assets_prefix . 'general-settings.php';
	}

	function wpsd_template_settings()
	{
		require_once WPSD_PATH . 'admin/view/' . $this->wpsd_assets_prefix . 'template-settings.php';
	}

	function wpsd_all_donations()
	{
		$wpsdColumns = array(
			'wpsd_donated_amount' 		=> esc_html__('Amount', WPSD_TXT_DOMAIN),
			'&nbsp;'					=> esc_html__('&nbsp;', WPSD_TXT_DOMAIN),
			'wpsd_donation_for'			=> esc_html__('Donation For', WPSD_TXT_DOMAIN),
			'wpsd_donator_name'			=> esc_html__('Name', WPSD_TXT_DOMAIN),
			'wpsd_donator_email'		=> esc_html__('Email', WPSD_TXT_DOMAIN),
			'wpsd_donator_phone'		=> esc_html__('Phone', WPSD_TXT_DOMAIN),
			'wpsd_donation_datetime'	=> esc_html__('Date', WPSD_TXT_DOMAIN)
		);
		register_column_headers('wpsd-column-table', $wpsdColumns);
		require_once WPSD_PATH . 'admin/view/' . $this->wpsd_assets_prefix . 'all-donations.php';
	}

	protected function wpsd_get_all_donations()
	{
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->wpsdTable WHERE %d ORDER BY wpsd_id DESC LIMIT 0, 10", 1));
	}

	protected function wpsd_display_notification($type, $msg) { 
		?>
		<div class="wpsd-alert <?php printf('%s', $type); ?>">
			<span class="wpsd-closebtn">&times;</span>
			<strong><?php esc_html_e(ucfirst($type), WPSD_TXT_DOMAIN); ?>!</strong> <?php esc_html_e($msg, WPSD_TXT_DOMAIN); ?>
		</div>
		<?php 
	}

	function wpsd_get_image() {
		
		if (isset($_GET['id'])) {
			$image = wp_get_attachment_image(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), esc_html($_GET['img_type']), false, array('id' => esc_html($_GET['prev_id'])));
			$data = array(
				'image' => $image,
			);
			wp_send_json_success($data);
		} else {
			wp_send_json_error();
		}
	}

	function wpsd_get_help() {
		require_once WPSD_PATH . 'admin/view/' . $this->wpsd_assets_prefix . 'help-usage.php';
	}

	function wpsd_admin_sidebar() {
		?>
		<div class="wpsd-admin-sidebar" style="width: 277px; float: left; margin-top: 5px;">
			<div class="postbox">
				<h3 class="hndle"><span>Support / Bug / Customization</span></h3>
				<div class="inside centered">
					<p>Please feel free to let us know if you have any bugs to report. Your report / suggestion can make the plugin awesome!</p>
					<p style="margin-bottom: 1px! important;"><a href="https://hmplugin.com/contact-us/" target="_blank" class="button button-primary">Get Support</a></p>
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
?>