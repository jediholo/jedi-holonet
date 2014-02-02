<?php
/*
Plugin Name: JEDI Plugins
Description: A collection of Wordpress extensions.
Version: 1.0
Author: Fabien CRESPEL
Author URI: http://www.jediholo.net
*/

require_once('functions.php');

function jedi_plugins_init() {
	wp_enqueue_style('jedi-plugins', plugins_url('jedi-plugins.css', __FILE__));
	add_shortcode('search', 'jedi_search_shortcode');
}
add_action('init', 'jedi_plugins_init');

function jedi_plugins_widgets_init() {
	$widgets = array(
		'JEDI_Widget_Context',
		'JEDI_Widget_Pages',
		'JEDI_Widget_Recent_Pages',
		'JEDI_Widget_Recent_Posts',
		'JEDI_Widget_Tracker',
	);

	foreach ($widgets as $widget) {
		require_once("widgets/$widget.php");
		register_widget($widget);
	}
}
add_action('widgets_init', 'jedi_plugins_widgets_init');
