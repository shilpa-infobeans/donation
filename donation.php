<?php

/**
 * Plugin Name:       Donation
 * Plugin URI:        https://www.donation.com/
 * Description:       This plugin is designed for donation.
 * Version:           1.2.0
 * Author:            Shilpa Choudhary
 * Author URI:        https://www.donation.com/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! defined( 'PLUGIN_DONATION_DIR' ) ) {
    define('PLUGIN_DONATION_DIR', plugin_dir_path(__FILE__));
}

if ( ! defined( 'PLUGIN_DONATION_URL' ) ) {
    define('PLUGIN_DONATION_URL', plugin_dir_url(__FILE__));
}

require PLUGIN_DONATION_DIR . '/inc/setup.php';
require PLUGIN_DONATION_DIR . '/template/load-templates.php';
//require PLUGIN_DONATION_DIR . '/template/donation_history_report.php';

require PLUGIN_DONATION_DIR . '/constant.php';

setup();

function is_slug_exist(){

	global $wpdb;
	$table = $wpdb->prefix . 'posts'; 
	$safe_sql = $wpdb->prepare( "SELECT post_name FROM {$table} WHERE `post_name` IN ( %s, %s)",[ DONATION_FORM, THANK_YOU ] );

	$page_slug =  $wpdb->get_results( $safe_sql, ARRAY_A );

	if ( is_array( $page_slug ) && !empty( $page_slug ) && count($page_slug) >= 2) {
		return true;
	} else {
        return false;
    }
}

function add_my_custom_page_ome() {
	$pages = [
				[	
					'post_title' => 'Donation Form',
					'post_content' => '[donation_form]',
				],
				[	'post_title' => 'Thank you Donation',
					'post_content' => 'Buy!',
				],
			];

	  foreach( $pages as $page ) {
		if ( ! is_slug_exist()) {
			$new_post = array(
				'post_title'    => $page['post_title'],
				'post_content'  => $page['post_content'],
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
	
			wp_insert_post( $new_post );
		}
	}

}
register_activation_hook(__FILE__,  'add_my_custom_page_ome');

function donation_management_plugin_activate() {
    do_action( 'donation_management_plugin_activate' );
}
register_activation_hook( __FILE__, 'donation_management_plugin_activate' );