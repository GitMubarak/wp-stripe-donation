<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Wpsd_Front {

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

		if ( ! wp_script_is('jquery') ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script('checkout-stripe-js', '//js.stripe.com/v3/');

		wp_enqueue_script(
			$this->wpsd_assets_prefix . 'front-script',
			WPSD_ASSETS . 'js/' . $this->wpsd_assets_prefix . 'front-script.js',
			array('jquery'),
			$this->wpsd_version,
			TRUE
		);

		$wpsdKeySettings		= stripslashes_deep( unserialize( get_option('wpsd_key_settings') ) );
		$wpsdPrimaryKey 		= isset( $wpsdKeySettings['wpsd_private_key'] ) ? $wpsdKeySettings['wpsd_private_key'] : '';
		$wpsdSecretKey 			= isset( $wpsdKeySettings['wpsd_secret_key'] ) ? $wpsdKeySettings['wpsd_secret_key'] : '';

		$wpsdGeneralSettings	= stripslashes_deep( unserialize( get_option('wpsd_general_settings') ) );
		$wpsdDonateCurrency 	= isset( $wpsdGeneralSettings['wpsd_donate_currency'] ) ? $wpsdGeneralSettings['wpsd_donate_currency'] : 'USD';

		$wpsdAdminArray = array(
			'stripePKey'	=> $wpsdPrimaryKey,
			'stripeSKey'	=> $wpsdSecretKey,
			'ajaxurl' 		=> admin_url('admin-ajax.php'),
			'currency'		=> $wpsdDonateCurrency,
			'successUrl'	=> get_site_url() . '/wpsd-thank-you',
			'idempotency' 	=> $this->wpsd_rand_string(8),
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

		if (
			! empty( $_POST['wpsdSecretKey'] ) 
			&& ! empty( $_POST['email'] ) 
			&& ! empty( $_POST['amount'] ) 
			&& ! empty( $_POST['name'] ) 
			&& ! empty( $_POST['donation_for'] )
		) {
			
			$wpsdDonationFor 	= sanitize_text_field( $_POST['donation_for'] );
			$wpsdName 			= sanitize_text_field( $_POST['name'] );
			$wpsdEmail 			= sanitize_email( $_POST['email'] );
			$wpsdAmount 		= filter_var( $_POST['amount'], FILTER_SANITIZE_STRING );
			$wpsdCurrency 		= sanitize_text_field( $_POST['currency'] );
			$wpsdStripeKey 		= sanitize_text_field( $_POST['wpsdSecretKey'] );
			$idempotency 		= preg_replace('/[^a-z\d]/im', '', $_POST['idempotency']);
			
			require_once( WPSD_PATH . 'front/stripe/init.php' );
			
			\Stripe\Stripe::setApiKey( base64_decode( $wpsdStripeKey ) );

			try {
				$intent = \Stripe\PaymentIntent::create([
					'amount' 		=> $wpsdAmount * 100,
					'currency' 		=> $wpsdCurrency,
					'description'	=> $wpsdDonationFor,
					'receipt_email'	=> $wpsdEmail,
					// Verify your integration in this guide by including this parameter
					'metadata' 		=> ['integration_check' => 'accept_a_payment'],
				], [
					'idempotency_key' => $idempotency
				] );
				
				if ( '' !== $intent->client_secret ) {
					die( json_encode( array(
						'status' => 'success',
						'client_secret' => $intent->client_secret
					) ) );
				} else {
					die( json_encode( array(
						'status' => 'error',
						'message' => 'Something went wrong!'
					) ) );
				}
			} 
			catch ( \Stripe\Exception\CardException $e ) {
				echo '<pre>';
				print_r( $e );
			}
			catch (\Stripe\Exception\RateLimitException $e) {
			  	// Too many requests made to the API too quickly
				echo '<pre>';
				print_r( $e );
			} catch (\Stripe\Exception\InvalidRequestException $e) {
			  	// Invalid parameters were supplied to Stripe's API
			  	echo '<pre>';
				print_r( $e );
			} catch (\Stripe\Exception\AuthenticationException $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				echo '<pre>';
				print_r( $e );
			} catch (\Stripe\Exception\ApiConnectionException $e) {
			  	// Network communication with Stripe failed
			  	echo '<pre>';
				print_r( $e );
			} catch (\Stripe\Exception\ApiErrorException $e) {
			  	// Display a very generic error to the user, and maybe send
			  	// yourself an email
			  	echo '<pre>';
				print_r( $e );
			} catch (Exception $e) {
			  	// Something else happened, completely unrelated to Stripe
			  	echo '<pre>';
				print_r( $e );
			}
		}
	}

	function wpsd_donation_handler_success() {

		if (
			! empty( $_POST['email'] ) 
			&& ! empty( $_POST['amount'] ) 
			&& ! empty( $_POST['name'] ) 
			&& ! empty( $_POST['donation_for'] )
		) {
			
			$wpsdDonationFor 	= sanitize_text_field( $_POST['donation_for'] );
			$wpsdName 			= sanitize_text_field( $_POST['name'] );
			$wpsdEmail 			= sanitize_email( $_POST['email'] );
			$wpsdAmount 		= filter_var( $_POST['amount'], FILTER_SANITIZE_STRING );
			$wpsdCurrency 		= sanitize_text_field( $_POST['currency'] );

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
			$this->wpsd_save_donation_info( $wpsdDonationFor, $wpsdName, $wpsdEmail, $wpsdAmount, $wpsdCurrency );

			// Upon Successful transaction, reply an Success message
			die( json_encode( array( 'status' => 'success' ) ) );
		}
	}

	function wpsd_save_donation_info( $wpsdDonationFor, $wpsdName, $wpsdEmail, $wpsdAmount, $wpsdCurrency ) {

		global $wpdb;

		return $wpdb->query('INSERT INTO ' . WPSD_TABLE . '(
			wpsd_donation_for,
			wpsd_donator_name,
			wpsd_donator_email,
			wpsd_donator_phone,
			wpsd_donated_amount,
			wpsd_donation_datetime
		) VALUES (
			"' . $wpsdDonationFor . '",
			"' . $wpsdName . '",
			"' . $wpsdEmail . '",
			"' . $wpsdCurrency . '",
			"' . $wpsdAmount . '",
			"' . date('Y-m-d h:i:s') . '"
		)');
	}

	function  wpsd_email_to_admin( $wpsdDonationEmail, $wpsdName, $wpsdAmount, $wpsdCurrency, $wpsdDonationFor, $wpsdEmail ) {
		
		$headers = array('Content-Type: text/html; charset=UTF-8');

		$wpsdEmailSubject = __('New Donation Received!');
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

	function wpsd_rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}
}