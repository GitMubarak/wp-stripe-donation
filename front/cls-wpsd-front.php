<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	Front CLass
 */
class Wpsd_Front
{
	use HM_Currency;

	private $wpsd_version;

	function __construct( $version ) {
		$this->wpsd_version = $version;
		$this->wpsd_assets_prefix = substr(WPSD_PRFX, 0, -1) . '-';
	}

	function wpsd_front_assets() {

		wp_enqueue_style(
			$this->wpsd_assets_prefix . 'front-style',
			WPSD_ASSETS . 'css/' . $this->wpsd_assets_prefix . 'front-style.css',
			array(),
			$this->wpsd_version,
			FALSE
		);

		if (!wp_script_is('jquery')) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script('checkout-stripe-js', '//checkout.stripe.com/checkout.js');
		wp_enqueue_script(
			$this->wpsd_assets_prefix . 'front-script',
			WPSD_ASSETS . 'js/' . $this->wpsd_assets_prefix . 'front-script.js',
			array('jquery'),
			$this->wpsd_version,
			TRUE
		);

		$wpsdKeySettings = stripslashes_deep(unserialize(get_option('wpsd_key_settings')));
		if (is_array($wpsdKeySettings)) {
			$wpsdPrimaryKey = !empty($wpsdKeySettings['wpsd_private_key']) ? $wpsdKeySettings['wpsd_private_key'] : "";
			$wpsdSecretKey = !empty($wpsdKeySettings['wpsd_secret_key']) ? $wpsdKeySettings['wpsd_secret_key'] : "";
		} else {
			$wpsdPrimaryKey = "";
			$wpsdSecretKey = "";
		}
		$wpsdGeneralSettings = stripslashes_deep(unserialize(get_option('wpsd_general_settings')));
		if (is_array($wpsdGeneralSettings)) {
			$wpsdPaymentTitle = $wpsdGeneralSettings['wpsd_payment_title'];
			$wpsdPaymentLogo = $wpsdGeneralSettings['wpsd_payment_logo'];
			$wpsdDonateCurrency = $wpsdGeneralSettings['wpsd_donate_currency'];
		} else {
			$wpsdPaymentTitle = "Donate Us";
			$wpsdPaymentLogo = "";
			$wpsdDonateCurrency = "USD";
		}
		$wpsdImage = array();
		$image = "";
		if (intval($wpsdPaymentLogo) > 0) {
			$wpsdImage = wp_get_attachment_image_src($wpsdPaymentLogo, 'thumbnail', false);
			$image = $wpsdImage[0];
		} else {
			$image = WPSD_ASSETS . 'img/stripe-default-logo.png';
		}
		$wpsdAdminArray = array(
			'stripePKey'	=> $wpsdPrimaryKey,
			'stripeSKey'	=> $wpsdSecretKey,
			'image'			=> $image,
			'ajaxurl' 		=> admin_url('admin-ajax.php'),
			'title'			=> $wpsdPaymentTitle,
			'currency'		=> $wpsdDonateCurrency,
			'successUrl'	=> get_site_url() . '/wpsd-thank-you',
		);
		wp_localize_script($this->wpsd_assets_prefix . 'front-script', 'wpsdAdminScriptObj', $wpsdAdminArray);
	}

	function wpsd_load_shortcode() {
		add_shortcode('wp_stripe_donation', array($this, 'wpsd_load_shortcode_view'));
	}

	function wpsd_load_shortcode_view() {
		$output = '';
		ob_start();
		include(plugin_dir_path(__FILE__) . '/view/wpsd-front-view.php');
		$output .= ob_get_clean();
		return $output;
	}

	function wpsd_donation_handler() {

		// Checking all required fields
		if (
			! empty( $_POST['token'] ) 
			&& ! empty( $_POST['wpsdSecretKey'] ) 
			&& ! empty( $_POST['email'] ) 
			&& ! empty( $_POST['amount'] ) 
			&& ! empty( $_POST['name'] ) 
			&& ! empty( $_POST['donation_for'] )
		) {
			
			$wpsdDonationFor 	= sanitize_text_field( $_POST['donation_for'] );
			$wpsdName 			= sanitize_text_field( $_POST['name'] );
			$wpsdEmail 			= sanitize_email( $_POST['email'] );
			$wpsdPhone 			= sanitize_text_field( $_POST['phone'] );
			$wpsdAmount 		= filter_var( $_POST['amount'], FILTER_SANITIZE_STRING );
			$wpsdCurrency 		= sanitize_text_field( $_POST['currency'] );
			$wpsdStripeKey 		= sanitize_text_field( $_POST['wpsdSecretKey'] );
			$wpsdToken 			= sanitize_text_field( $_POST['token'] );
			
			require_once( WPSD_PATH . 'front/Stripe/Stripe.php' );
			
			Stripe::setApiKey( base64_decode( $wpsdStripeKey ) );

			// Transaction starting
			try {
				
				Stripe_Charge::create( array(
					'amount' 			=> __($wpsdAmount * 100),
					'currency' 			=> __($wpsdCurrency),
					'source' 			=> __($wpsdToken),
					'description' 		=> __($wpsdName) . __(' donated for ', WPSD_TXT_DOMAIN) . __($wpsdDonationFor),
					'receipt_email'		=> __($wpsdEmail)
				));
				
				$wpsdGeneralSettings 	= stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );
				$wpsdDonationEmail 		= isset( $wpsdGeneralSettings['wpsd_donation_email'] ) ? $wpsdGeneralSettings['wpsd_donation_email'] : '';
				
				// Send email to admin
				if ( '' !== $wpsdDonationEmail ) {
					$this->wpsd_email_to_admin( $wpsdDonationEmail, $wpsdName, $wpsdAmount, $wpsdCurrency, $wpsdDonationFor, $wpsdEmail );
				}

				// Send email to client
				if ( '' !== $wpsdEmail ) {
					$this->wpsd_email_to_client( $wpsdEmail, $wpsdName, $wpsdAmount, $wpsdCurrency, $wpsdDonationFor );
				}
				
				// Save data to database
				$this->wpsd_save_donation_info( $wpsdDonationFor, $wpsdName, $wpsdEmail, $wpsdAmount );
				

				// Upon Successful transaction, reply an Success message
				die( json_encode( array(
					"status" => "success",
					"message" => "Thank you for your donation"
				) ) );

			} catch ( Stripe_CardError $e ) {

				// Upon unsuccessful transaction/rejection, reply an Error message
				die( json_encode( array(
					"status" => "error",
					"message" => $e
				) ) );
			}
		}
	}

	function wpsd_save_donation_info( $wpsdDonationFor, $wpsdName, $wpsdEmail, $wpsdAmount ) {

		global $wpdb;

		return $wpdb->query('INSERT INTO ' . WPSD_TABLE . '(
			wpsd_donation_for,
			wpsd_donator_name,
			wpsd_donator_email,
			wpsd_donated_amount,
			wpsd_donation_datetime
		) VALUES (
			"' . $wpsdDonationFor . '",
			"' . $wpsdName . '",
			"' . $wpsdEmail . '",
			"' . $wpsdAmount . '",
			"' . date('Y-m-d h:i:s') . '"
		)');
	}

	function  wpsd_email_to_admin( $wpsdDonationEmail, $wpsdName, $wpsdAmount, $wpsdCurrency, $wpsdDonationFor, $wpsdEmail ) {
		
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$wpsdEmailSubject = __('You have a Donation for - ') . $wpsdDonationFor;
		$wpsdEmailMessage = __('Name: ') . $wpsdName;
		$wpsdEmailMessage .= '<br>' . __('Email: ') . $wpsdEmail;
		$wpsdEmailMessage .= '<br>' . __('Amount: ') . $wpsdAmount . $wpsdCurrency;
		$wpsdEmailMessage .= '<br>' . __('For: ') . $wpsdDonationFor;

		return wp_mail( $wpsdDonationEmail, $wpsdEmailSubject, $wpsdEmailMessage, $headers );
	}

	function wpsd_email_to_client( $wpsdEmail, $wpsdName, $wpsdAmount, $wpsdCurrency, $wpsdDonationFor ) {

		$headers = array('Content-Type: text/html; charset=UTF-8');

		$donorEmailSubject = __('Thank you for your donation');
		$donorEmailMessage = __('Hello ') . $wpsdName;
		$donorEmailMessage .= '<br>' . __('Thank you for your donation');
		$donorEmailMessage .= '<br>' . __('Donated amount: ') . $wpsdAmount . $wpsdCurrency;
		$donorEmailMessage .= '<br>' . __('Donated for: ') . $wpsdDonationFor;
		
		return wp_mail( $wpsdEmail, $donorEmailSubject, $donorEmailMessage, $headers );
	}

	function wpsd_donation_success_template( $template ) {

		global $post;
		
		if ( 'wpsd-thank-you' == $post->post_name ) {
			return WPSD_PATH . 'front/view/wpsd-donation-success.php';
		}

		return $template;

	}
}