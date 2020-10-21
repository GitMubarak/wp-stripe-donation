<?php

/**
 *	Front CLass
 */
class Wpsd_Front
{
	use HM_Currency;

	private $wpsd_version;

	public function __construct($version)
	{
		$this->wpsd_version = $version;
		$this->wpsd_assets_prefix = substr(WPSD_PRFX, 0, -1) . '-';
	}

	public function wpsd_front_assets()
	{
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
		);
		wp_localize_script($this->wpsd_assets_prefix . 'front-script', 'wpsdAdminScriptObj', $wpsdAdminArray);
	}

	public function wpsd_load_shortcode()
	{
		add_shortcode('wp_stripe_donation', array($this, 'wpsd_load_shortcode_view'));
	}

	public function wpsd_load_shortcode_view()
	{
		$output = '';
		ob_start();
		include(plugin_dir_path(__FILE__) . '/view/wpsd-front-view.php');
		$output .= ob_get_clean();
		return $output;
	}

	function wpsd_donation_handler()
	{
		global $wpdb;
		$tableData = WPSD_TABLE;
		/*
		* Validation all required fields
		*/
		if (
			!empty($_POST['token']) && !empty($_POST['wpsdSecretKey']) && !empty($_POST['email']) && !empty($_POST['amount']) && !empty($_POST['name']) &&
			!empty($_POST['donation_for'])
		) {

			$wpsdDonationFor = sanitize_text_field($_POST['donation_for']);
			$wpsdName = sanitize_text_field($_POST['name']);
			$wpsdEmail = sanitize_email($_POST['email']);
			$wpsdPhone = intval($_POST['phone']);
			$wpsdAmount = intval($_POST['amount']);
			$wpsdCurrency = sanitize_text_field($_POST['currency']);

			require_once "Stripe/Stripe.php";
			include(WPSD_PATH . '/Stripe/Stripe.php');
			$stripe_key = sanitize_text_field($_POST['wpsdSecretKey']);
			Stripe::setApiKey(base64_decode($stripe_key));

			// Credit card details
			$token = sanitize_text_field( $_POST['token'] );

			// Transaction starting
			try {
				$charge = Stripe_Charge::create(
					array(
						"amount" 		=> $wpsdAmount,
						"currency" 		=> $wpsdCurrency,
						"card"			=> $token,
						"description"	=> $wpsdName . " (" . $wpsdPhone . ") donated for " . $wpsdDonationFor,
						//"customer" 		=> 'cus_HOG2fWT30UC0nE'
					)
				);
				$cu = Stripe_Customer::retrieve( 'cus_HOG2fWT30UC0nE' );
				echo '<pre>';
						print_r($cu);
				/*
				$customer = Stripe_Customer::create(
					array(
						'card' => $token,
						'email' => $wpsdEmail,
						'name'	=> $wpsdName,
						'phone'	=> $wpsdPhone
					)
				);
				$customer_id = $customer->id;
				if( $customer_id ) {
						$charge = Stripe_Charge::create(
							array(
								"amount" 		=> $wpsdAmount,
								"currency" 		=> $wpsdCurrency,
								//"card"			=> $token,
								"description"	=> $wpsdName . " (" . $wpsdPhone . ") donated for " . $wpsdDonationFor,
								"customer" 		=> $customer_id
							)
						);
						echo '<pre>';
						print_r($charge);
				}
				*/
				/*
				$wpsdGeneralSettings = stripslashes_deep(unserialize(get_option('wpsd_general_settings')));
				if (is_array($wpsdGeneralSettings)) {
					$wpsdDonationEmail = !empty($wpsdGeneralSettings['wpsd_donation_email']) ? $wpsdGeneralSettings['wpsd_donation_email'] : "";
				}
				// Send the email if the charge successful.
				$wpsdEmailSubject = "New Donation for " . $wpsdDonationFor;
				$wpsdEmailMessage = "Name: " . $wpsdName . "<br>Email: " . $wpsdEmail . "<br>Amount: " . substr($wpsdAmount, 0, -2) . $wpsdCurrency . "<br>For: " . $wpsdDonationFor . "<br>";
				if ($wpsdPhone) {
					$wpsdEmailMessage .= "Phone: " . $wpsdPhone . "<br>";
				}
				$headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail($wpsdDonationEmail, $wpsdEmailSubject, $wpsdEmailMessage, $headers);
				$wpdb->query('INSERT INTO ' . $tableData . ' (
															wpsd_donation_for,
															wpsd_donator_name,
															wpsd_donator_email,
															wpsd_donator_phone,
															wpsd_donated_amount,
															wpsd_donation_datetime
														)
												VALUES(
															"' . $wpsdDonationFor . '",
															"' . $wpsdName . '",
															"' . $wpsdEmail . '",
															"' . $wpsdPhone . '",
															"' . substr($wpsdAmount, 0, -2) . '",
															"' . date('Y-m-d h:i:s') . '"
													)
												');

				// Upon Successful transaction, reply an Success message
				die(json_encode(array(
					"status" => "success",
					"message" => "Thank you for your donation"
				)));
				*/
			} catch( Stripe_CardError $e ) {

				// Upon unsuccessful transaction/rejection, reply an Error message
				die( json_encode( array(
					"status" => "error",
					"message" => $e
				) ) );
			}
		}
	}
}