<?php
/*
Plugin Name: JEDI Plugins
Description: A collection of Wordpress extensions for the JEDI HoloNet.
Version: 1.3
Author: Fabien CRESPEL
Author URI: https://www.jediholo.net
*/

require_once(__DIR__ . '/functions.php');

// Main init
function jedi_plugins_init() {
	wp_enqueue_style('jedi-plugins', plugins_url('public/jedi-plugins.css', __FILE__));
	wp_enqueue_script('jedi-plugins', plugins_url('public/jedi-plugins.js.php', __FILE__), array('jquery'));
	add_shortcode('search', 'jedi_search_shortcode');
	add_shortcode('rpmod-accounts', 'jedi_accounts_shortcode');
	add_shortcode('rpmod-server', 'jedi_server_shortcode');
}
add_action('init', 'jedi_plugins_init');

// Widgets init
function jedi_plugins_widgets_init() {
	$widgets = array(
		'JEDI_Widget_Context',
		'JEDI_Widget_Page',
		'JEDI_Widget_Pages',
		'JEDI_Widget_Recent_Pages',
		'JEDI_Widget_Recent_Posts',
		'JEDI_Widget_Tracker',
	);

	foreach ($widgets as $widget) {
		require_once(__DIR__ . "/widgets/$widget.php");
		register_widget($widget);
	}
}
add_action('widgets_init', 'jedi_plugins_widgets_init');

// Date filter
function jedi_plugins_wp_date( $date, $format, $timestamp, $timezone ) {
	return jedi_parse_date_format($date, $timestamp, $timezone);
}
add_filter('wp_date', 'jedi_plugins_wp_date', 10, 4);

// OpenID Connect button text filter
function jedi_plugins_openid_connect_button( $text ) {
	return 'Login with RPMod';
}
add_filter('openid-connect-generic-login-button-text', 'jedi_plugins_openid_connect_button');

// OpenID Connect user login test filter
function jedi_plugins_openid_connect_user_login_test( $result, $user_claim ) {
	return (isset($user_claim['communities']) && is_array($user_claim['communities']) && in_array('jedi', $user_claim['communities']));
}
add_filter('openid-connect-generic-user-login-test', 'jedi_plugins_openid_connect_user_login_test', 10, 2);

// OpenID Connect update user action
function jedi_plugins_openid_connect_update_user( $user, $user_claim ) {
	if (isset($user_claim['admin_rank_num']) && !in_array('administrator', $user->roles)) {
		if (intval($user_claim['admin_rank_num']) >= 5) {
			// Grant Editor role to Council/God admins
			$user->set_role('editor');
		} else {
			// Reset role to Subscriber
			$user->set_role('subscriber');
		}
	}
}
add_action('openid-connect-generic-update-user-using-current-claim', 'jedi_plugins_openid_connect_update_user', 10, 2);

// Big image size threshold
function jedi_plugins_big_image_size_threshold( $threshold, $imagesize, $file, $attachment_id ) {
	return 7680; // 8K
}
add_filter('big_image_size_threshold', 'jedi_plugins_big_image_size_threshold', 10, 4);
