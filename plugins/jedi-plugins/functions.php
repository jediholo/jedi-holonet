<?php
require_once(__DIR__ . '/include/IconList.class.php');
require_once(__DIR__ . '/include/RPModAccountServiceClient.class.php');


/***************************/
/* Miscellaneous functions */
/***************************/

function jedi_get_page_id_by_name($name) {
	$page = get_page_by_path($name);
	return $page ? $page->ID : 0;
}

function jedi_get_attachment_by_name($name) {
	$att = get_posts("post_type=attachment&attachment={$name}&name={$name}");
	return (count($att) > 0) ? $att[0] : null;
}

function jedi_get_the_title_ellipsis($max) {
	$title = get_the_title();
	if ($max > 0 && strlen($title) > $max) {
		return substr($title,  0, $max-3) . '&hellip;';
	} else {
		return $title;
	}
}


/**************/
/* Shortcodes */
/**************/

function jedi_search_shortcode($atts, $content, $shortcode_tag) {
	$query = get_search_query();
	$url = get_bloginfo('url');
	return <<<EOF
<form method="get" id="searchform" action="$url/">
  <input type="text" value="$query" name="s" id="s" />
  <input type="submit" id="searchsubmit" value="Search" />
  <div style="clear: both;"></div>
</form>
EOF;
}

function jedi_accounts_shortcode($atts, $content, $shortcode_tag) {
	// Build filter
	$filter = 'rank.ordinal > 0';
	if (isset($atts['community']) && !empty($atts['community'])) {
		$filter .= ', community.name = "' . $atts['community'] . '"';
	}

	// Call the Web Service
	try {
		$userClient = new RPModAccountServiceClient();
		$users = $userClient->getUsers(0, 100, 'rank.ordinal desc, character.fullName asc', $filter);
	} catch (Exception $e) {
		return "<p><strong>ERROR:</strong> {$e->getMessage()}</p>\n";
	}

	// Print all accounts, grouped by rank
	$ret = '';
	$curRank = -1;
	$curList = null;
	foreach ($users as $user) {
		// Check for new rank
		if ($user->Character->Rank->ordinal != $curRank) {
			if ($curList !== null) {
				$ret .= $curList;
			}
			$ret .= '<h3>' . $user->Character->Rank->displayName . "</h3>\n";
			$curRank = $user->Character->Rank->ordinal;
			$curList = new JEDI_IconList();
		}

		// Init item properties
		$name = isset($user->Character->fullName) ? $user->Character->fullName : $user->userName;
		$class = isset($user->Character->Class) ? $user->Character->Class->name : '';
		$permalink = '';
		$species = '';
		$icon = '';
		$subtitle2 = $class;
		
		// Get information from the biography page
		$pages = get_pages('hierarchical=0&meta_key=username&meta_value=' . $user->userName);
		if ($pages) {
			$permalink = get_permalink($pages[0]->ID);
			$species = get_post_meta($pages[0]->ID, 'species', true);
			$icon = get_post_meta($pages[0]->ID, 'icon', true);
			$title = get_post_meta($pages[0]->ID, 'title', true);
			if (!empty($title)) $subtitle2 = $title;
			$present[] = $pages[0]->ID;
		} else {
			$missing[] = $name;
		}
		if (empty($icon)) $icon = $user->userName . '_icon';
		
		// Create and add the item to the list
		$item = new JEDI_IconItem($name);
		$item->setGroup('rank' . $user->Character->Rank->ordinal);
		$item->setLink($permalink);
		$item->setSubtitle1($species);
		$item->setSubtitle2($subtitle2);
		$item->setIcon($icon);
		$curList->addItem($item);
	}
	if ($curList !== null) {
		$ret .= $curList;
	}

	// Display a special maintenance section for admins
	global $post;
	if (is_user_logged_in() && current_user_can('edit_page', $post->ID)) {
		$ret .= "<h3>Maintenance</h3>\n";
		
		// Check which children of this page are not in the list
		$children = get_pages('child_of=' . $post->ID);
		$lostones = array();
		foreach ($children as $child) {
			if (!in_array($child->ID, $present)) {
				$lostones[] = $child;
			}
		}
		
		// Missing bios
		$ret .= "<div class=\"box alignleft\" style=\"width: 45%\">\n";
		$ret .= "<p><strong>Missing Biographies</strong></p>\n";
		$ret .= "<p>The following Residents have no biography page, or their page is missing the 'username' custom field:</p>\n";
		if (!$missing) {
			$ret .= "<p>None.</p>\n";
		} else {
			$ret .= "<ul>\n";
			foreach ($missing as $name) {
				$ret .= "<li>{$name}</li>\n";
			}
			$ret .= "</ul>\n";
		}
		$ret .= "</div>\n";
		
		// Lost Ones
		$ret .= "<div class=\"box alignright\" style=\"width: 45%\">\n";
		$ret .= "<p><strong>Lost Ones</strong></p>\n";
		$ret .= "<p>The following sub-pages are no longer Residents (i.e. they should be moved to the Archives), or are missing the 'username' custom field:</p>\n";
		if (!$lostones) {
			$ret .= "<p>None.</p>\n";
		} else {
			$ret .= "<ul>\n";
			foreach ($lostones as $child) {
				$title = get_the_title($child->ID);
				$link = get_permalink($child->ID);
				$ret .= "<li><a href=\"{$link}\" title=\"{$title}\">{$title}</a></li>\n";
			}
			$ret .= "</ul>\n";
		}
		$ret .= "</div>\n";
		
		$ret .= "<div class=\"clear\"></div>\n";
	}

	return $ret;
}

function jedi_server_shortcode($atts, $content, $shortcode_tag) {
	$name = '';
	if (isset($atts['name'])) {
		$name = $atts['name'];
	} else if (isset($atts[0])) {
		$name = $atts[0];
	}

	$width = 500;
	if (isset($atts['width']) && !empty($atts['width'])) {
		$width = intval($atts['width']);
	}

	$height = 200;
	if (isset($atts['height']) && !empty($atts['height'])) {
		$height = intval($atts['height']);
	}

	return'<iframe src="https://rpmod.jediholo.net/server/tracker/name/' . $name . '" frameborder="0" scrolling="no" width="' . $width . '" height="' . $height . '"></iframe>';
}


/*********************/
/* JEDI date support */
/*********************/

function jedi_get_date(DateTimeInterface $date) {
	$baseRL = 2000;
	$baseSW = 153;

	$yearRL = intval($date->format('Y'));
	$monthRL = intval($date->format('n')); // 1 to 12
	$yearSW = $baseSW + (($yearRL - $baseRL) * 12) + $monthRL;
	$daySW = intval($date->format('j'));

	// Time freeze:
	// Year 461 (August 2025) is extended until the end of 2025, then January 2026 becomes year 462
	// Days are added every month, so December 31, 2025 will be 461.153
	if ($yearRL == 2025 && $monthRL >= 8) {
		$yearSW -= ($monthRL - 8); // skip remaining months of 2025
		$daySW = intval($date->format('z')) - 211; // number of days from January 1 to July 31, minus 1 (day of year is 0-based)
	} elseif ($yearRL > 2025) {
		$yearSW -= 4; // skip last 4 months of 2025
	}

	return $yearSW . '.' . str_pad($daySW, 2, "0", STR_PAD_LEFT);
}

function jedi_parse_date_format($format, $timestamp, $timezone) {
	$date = DateTimeImmutable::createFromFormat('U', $timestamp, $timezone);
	$jedi_date = jedi_get_date($date);
	return preg_replace('/\\$J/', $jedi_date, $format);
}
