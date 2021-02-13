<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
    die;
}

global $wpdb;
$option_name    = 'wporg_option';
$table_name     = $wpdb->prefix . 'wpsd_stripe_donation';
$tbl            = $wpdb->prefix . 'options';
$search_string  = 'wpsd_%';

$sql            = $wpdb->prepare("SELECT option_name FROM $tbl WHERE option_name LIKE %s", $search_string);
$options        = $wpdb->get_results($sql, OBJECT);

if ( is_array( $options ) && count( $options ) ) {
    foreach ( $options as $option ) {
        delete_option( $option->option_name );
        delete_site_option( $option->option_name );
    }
}

// drop a custom database table
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");
?>