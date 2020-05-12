<?php

/**
 * Plugin Name: 	WP Stripe Donation
 * Plugin URI:		http://wordpress.org/plugins/wp-stripe-donation/
 * Description: 	This WordPress Stripe Donation is a simple plugin that allows you to collect donations on your website via Stripe payment method.
 * Version: 		1.3
 * Author: 			Hossni Mubarak
 * Author URI: 		http://www.hossnimubarak.com
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('WPINC')) {
    die;
}
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

define('WPSD_PATH', plugin_dir_path(__FILE__));
define('WPSD_ASSETS', plugins_url('/assets/', __FILE__));
define('WPSD_LANG', plugins_url('/languages/', __FILE__));
define('WPSD_SLUG', plugin_basename(__FILE__));
define('WPSD_PRFX', 'wpsd_');
define('WPSD_CLS_PRFX', 'cls-wpsd-');
define('WPSD_TXT_DOMAIN', 'wp-stripe-donation');
define('WPSD_VERSION', '1.3');
define('WPSD_TABLE', $wpdb->prefix . 'wpsd_stripe_donation');

require_once WPSD_PATH . 'inc/' . WPSD_CLS_PRFX . 'master.php';
$wpsd = new Wpsd_Master();
register_activation_hook(__FILE__, array($wpsd, WPSD_PRFX . 'install_table'));
$wpsd->wpsd_run();
register_deactivation_hook(__FILE__, array($wpsd, WPSD_PRFX . 'unregister_settings'));