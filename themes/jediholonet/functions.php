<?php

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
<script src="//static.jediholo.net/libs/jquery.colorbox-1.5.14/jquery.colorbox-min.js" type="text/javascript"></script>
<script src="//static.jediholo.net/js/global-min.js" type="text/javascript"></script>
<script type="text/javascript">
// <![CDATA[
timezone = $timezone;
// ]]>
</script>
EOF;
}
add_action('wp_head', 'my_head');

function my_footer() {
	echo <<<EOF
<script type="text/javascript">
jQuery('.iconitem a.lbpModal').colorbox({opacity: 0.7, width: '800px', maxWidth: '80%', maxHeight: '90%', fixed: true, current: '{current} / {total}'});
jQuery(".post .entry a:has(img[class*='wp-image-'])").colorbox({opacity: 0.7, maxWidth: '80%', maxHeight: '90%', fixed: true, current: '{current} / {total}'});
</script>
EOF;
}
add_action('wp_footer', 'my_footer');


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
	for ($i = 1; $i <= 5; $i++) {
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

function get_attachment_by_name($name) {
	$att = get_posts("post_type=attachment&attachment={$name}&name={$name}");
	return (count($att) > 0) ? $att[0] : null;
}

function get_timezone_offset() {
	$date = new DateTime('now', wp_timezone());
	return $date->getOffset();
}


/*********************/
/* Wordpress filters */
/*********************/

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
