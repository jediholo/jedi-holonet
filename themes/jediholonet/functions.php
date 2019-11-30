<?php
require_once('include/config.inc.php');


/*************/
/* Init/Head */
/*************/

function my_init() {
	$locale = get_locale();
	setlocale(LC_CTYPE, $locale, $locale.'UTF-8', $locale.'UTF8', 'en_US', 'en_US.UTF-8', 'en_US.UTF8');
	wp_enqueue_script('jquery');
}
add_action('init', 'my_init');

function my_head() {
	$timezone = get_timezone_offset()*1000;
	echo <<<EOF
<script src="//static.jediholo.net/js/global-min.js" type="text/javascript"></script>
<script type="text/javascript">
// <![CDATA[
timezone = $timezone;
jQuery.ajaxSetup({cache: false});
// ]]>
</script>
EOF;
}
add_action('wp_head', 'my_head');


/************/
/* Sidebars */
/************/

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Header',
		'id' => 'sidebar-header',
		'description' => 'Widgets displayed in the main column, before articles',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	for ($i = 1; $i <= $GLOBALS['JEDI_config']['numSidebars']; $i++) {
		register_sidebar(array(
			'name' => "Sidebar $i",
			'id' => "sidebar-$i",
			'description' => 'Widgets displayed on the right of the main column',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
	}
}


/***************************/
/* Miscellaneous functions */
/***************************/

function isAjax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

function get_default_name() {
	return 'holonews';
}

function get_default_title() {
	return 'HoloNews';
}

function get_posts_url() {
	if (get_option('show_on_front') == 'page') {
		return get_permalink(get_option('page_for_posts'));
	} else {
		return get_option('home');
	}
}

function get_full_title($links = false, $hierarchy = true, $separator = ' &raquo; ') {
	$root = '';
	
	if ($hierarchy) {
		$root = get_bloginfo('blogname');
		if ($links) $root = '<a href="' . get_option('home') . '">' . $root . '</a>';
	}
	
	if (is_search()) {
		$title = $separator . 'Search results';
	} elseif (have_posts()) {
		$title = '';
		
		if ((is_home() && is_front_page()) || ((is_single() || is_archive()) && $hierarchy)) {
			$title = get_default_title();
			if ($links && !is_home()) {
				$url = get_posts_url();
				$title = '<a href="' . $url . '">' . $title . '</a>';
			}
			$title = $separator . $title;
		}  else if (is_page() && $hierarchy) {
			global $post;
			$ancestors = $post->ancestors;
			for ($i = count($ancestors)-1; $i >= 0; $i--) {
				$ancestorID = $ancestors[$i];
				$ancestorPost = get_post($ancestorID);
				$ancestorTitle = $ancestorPost->post_title;
				if ($links) $ancestorTitle = '<a href="' . get_permalink($ancestorID) . '">' . $ancestorTitle . '</a>';
				$title .= $separator . $ancestorTitle;
			}
		}
		
		// Current page
		$title .= wp_title($separator, false);
	} else {
		$title = $separator . 'Not Found';
	}
	return $root . $title;
}

function get_root_id() {
	$postID = -1;
	if (is_home() || is_single() || is_archive()) {
		$postID = 0;
	} else if (is_page()) {
		global $post;
		if ($post->ancestors)
			$postID = $post->ancestors[count($post->ancestors)-1];
		else
			$postID = $post->ID;
	}
	return $postID;
}

function get_root_name() {
	$id = get_root_id();
	$name = 'default';
	
	if ($id == 0) {
		$name = get_default_name();
	} else if ($id > 0) {
		$post = get_post($id);
		if ($post)
			$name = $post->post_name;
	}
	return $name;
}

function get_page_id_by_name($name) {
	$page = get_page_by_path($name);
	return $page ? $page->ID : 0;
}

function get_attachment_by_name($name) {
	$att = get_posts("post_type=attachment&attachment={$name}&name={$name}");
	return (count($att) > 0) ? $att[0] : null;
}

function get_the_title_ellipsis($max) {
	$title = get_the_title();
	if ($max > 0 && strlen($title) > $max) {
		return substr($title,  0, $max-3) . '&hellip;';
	} else {
		return $title;
	}
}



/*****************************/
/* SW/JEDI date/time support */
/*****************************/

function get_timezone_offset() {
	$date = new DateTime('now', new DateTimeZone('America/New_York'));
	return $date->getOffset();
}

function get_jedi_year($timestamp) {
	$baseRL = 2000;
	$baseSW = 153;

	$dateRL = getdate($timestamp);
	$yearOffset = $dateRL['year'] - $baseRL;
	$yearSW = $baseSW + ($yearOffset * 12) + $dateRL['mon'];

	return $yearSW;
}

function parse_jedi_format($format, $timestamp) {
	$jedi_year = get_jedi_year($timestamp);

	$format = ' ' . $format;
	$format = preg_replace( "/([^\\\])J/", '${1}' . $jedi_year, $format );
	$format = substr( $format, 1, strlen( $format ) -1 );

	return $format;
}



/*********************/
/* Wordpress filters */
/*********************/

function my_get_post_time( $value, $d = 'U', $gmt = false ) {
	global $post;

	$time = $gmt ? $post->post_date_gmt : $post->post_date;

	$timestamp = mysql2date('U', $time);
	$d = parse_jedi_format($d, $timestamp);

	return mysql2date($d, $time);
}
//add_filter('get_post_time', 'my_get_post_time', 10, 3);

function my_get_post_modified_time( $value, $d = 'U', $gmt = false ) {
	global $post;

	$time = $gmt ? $post->post_modified_gmt : $post->post_modified;
	
	$timestamp = mysql2date('U', $time);
	$d = parse_jedi_format($d, $timestamp);

	return mysql2date($d, $time);
}
//add_filter('get_post_modified_time', 'my_get_post_modified_time', 10, 3);

function my_the_date($the_date, $d='', $before='', $after='') {
	global $post;

	$the_date = '';
	if ($value != '') {
		$timestamp = mysql2date('U', $post->post_date);

		if ($d == '') $d = get_option('date_format');
		$d = parse_jedi_format($d, $timestamp);

		$the_date .= $before;
		$the_date .= mysql2date($d, $post->post_date);
		$the_date .= $after;
	}
	return $the_date;
}
//add_filter('the_date', 'my_the_date', 10, 4);

function my_get_comment_time( $value, $d = '', $gmt = false ) {
	global $comment;

	$comment_date = $gmt ? $comment->comment_date_gmt : $comment->comment_date;

	$timestamp = mysql2date('U', $comment_date);

	if ($d == '') $d = get_option('time_format');
	$d = parse_jedi_format($d, $timestamp);

	return mysql2date($d, $comment_date);
}
//add_filter('get_comment_time', 'my_get_comment_time', 10, 3);

function my_get_comment_date( $value, $d = '' ) {
	global $comment;

	$timestamp = mysql2date('U', $comment->comment_date);

	if ($d == '') $d = get_option('date_format');
	$d = parse_jedi_format($d, $timestamp);

	return mysql2date($d, $comment->comment_date);
}
//add_filter('get_comment_date', 'my_get_comment_date', 10, 2);

function my_date_i18n( $value, $dateformatstring, $unixtimestamp, $gmt ) {
	return parse_jedi_format($value, $unixtimestamp);
}
//add_filter('date_i18n', 'my_date_i18n', 10, 4);

function my_wp_date( $date, $format, $timestamp, $timezone ) {
	return parse_jedi_format($date, $timestamp);
}
add_filter('wp_date', 'my_wp_date', 10, 4);

function my_adjacent_post_link($value) {
	return "<li>{$value}</li>\n";
}
add_filter('previous_post_link', 'my_adjacent_post_link');
add_filter('next_post_link', 'my_adjacent_post_link');



/*******************************/
/* Navigation button functions */
/*******************************/

function make_nav_button($url, $name, $title, $help = '', $active = false) {
	$liClass = 'navItem' . ($active ? ' active' : '');
	$aClass = ($active ? 'active' : '');

	echo "    <li class=\"{$liClass}\" id=\"navItem-{$name}\"><a href=\"{$url}\" class=\"{$aClass}\">{$title}</a>\n";
	echo "      <ul><li>{$help}</li></ul>\n";
	echo "    </li>\n";
}

function make_nav_button_by_id($id, $help) {
	// Defaults
	$name = get_default_name();
	$title = get_default_title();
	$url = get_posts_url();
	
	// Get the page with the given ID
	if ($id != 0) {
		$page = get_post($id);
		if ($page) {
			$name = $page->post_name;
			$title = $page->post_title;
			$url = get_permalink($id);
		} else {
			$id = 0;
		}
	}
	
	// Check if the button should be active
	$active = (get_root_id() == $id);
	
	// Print the button code
	make_nav_button($url, $name, $title, $help, $active);
}

function make_nav_button_by_name($name, $help) {
	// Defaults
	$id = 0;
	$pagename = get_default_name();
	$title = get_default_title();
	$url = get_posts_url();
	
	// Get the page with the given name
	if (!empty($name)) {
		$page = get_page_by_path($name);
		if ($page) {
			$id = $page->ID;
			$pagename = $page->post_name;
			$title = $page->post_title;
			$url = get_permalink($id);
		}
	}
	
	// Check if the button should be active
	$active = (get_root_id() == $id);
	
	// Print the button code
	make_nav_button($url, $pagename, $title, $help, $active);
}
