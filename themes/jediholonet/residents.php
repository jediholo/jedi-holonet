<?php
/*
Template Name: Residents
*/
require_once(TEMPLATEPATH . '/include/RPMod.inc.php');
require_once(TEMPLATEPATH . '/include/IconList.class.php');
?>
<?php if (!isAjax()) : ?>
<?php get_header(); ?>

    <!-- Content header bar -->
    <div id="contentHeader">
      <!-- Page title -->
      <h2><?php echo get_full_title(true); ?></h2>
      <div class="clear"></div>
    </div>

<?php else : ?>
  <!-- Actual content -->
  <div class="content">
<?php endif; ?>

    <!-- Members list, generated with the RPMod service -->
    <div class="post entry">
<?php
try {
	$RPModClient = @ new SoapClient($GLOBALS['JEDI_config']['rpmod']['soapClientWSDL'], $GLOBALS['JEDI_config']['rpmod']['soapClientOptions']);
	
	// Get the Account list
	$response = $RPModClient->GetAccountList();
	
	// Create a simple array from the key/value pairs
	$accounts = array();
	$i = 0;
	foreach ($response as $account) {
		foreach ($account as $kvp) {
			$accounts[$i][$kvp->key] = $kvp->value;
		}
		$i++;
	}
	
	$present = array();
	$missing = array();
	
	// Print all accounts, grouped by rank, excluding Guests
	for ($i = 5; $i > 0; $i--) {
		echo "<h3>{$GLOBALS['rpmod_config']['ranks'][$i]}s</h3>\n";
		$list = new IconList();
		
		foreach ($accounts as $account) {
			if (isset($account['rank']) && $account['rank'] == $i) {
				$name = isset($account['fullName']) ? $account['fullName'] : $account['username'];
				$class = isset($account['className']) ? $account['className'] : '';
				$permalink = '';
				$species = '';
				$icon = '';
				$subtitle2 = $class;
				
				// Get information from the biography page
				$pages = get_pages('hierarchical=0&meta_key=username&meta_value=' . $account['username']);
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
				if (empty($icon)) $icon = $account['username'] . '_icon';
				
				// Create and add the item to the list
				$item = new IconItem($name);
				$item->setGroup("rank$i");
				$item->setLink($permalink);
				$item->setSubtitle1($species);
				$item->setSubtitle2($subtitle2);
				$item->setIcon($icon);
				$list->addItem($item);
			}
		}
		
		echo $list;
	}
	
	// Display a special maintenance section for admins
	if (is_user_logged_in() && current_user_can('edit_page', $post->ID)) {
		echo "<h3>Maintenance</h3>\n";
		
		// Check which children of this page are not in the list
		$children = get_pages('child_of=' . $post->ID);
		$lostones = array();
		foreach ($children as $child) {
			if (!in_array($child->ID, $present)) {
				$lostones[] = $child;
			}
		}
		
		// Missing bios
		echo "<div class=\"box alignleft\" style=\"width: 45%\">\n";
		echo "<p><strong>Missing Biographies</strong></p>\n";
		echo "<p>The following Residents have no biography page, or their page is missing the 'username' custom field:</p>\n";
		if (!$missing) {
			echo "<p>None.</p>\n";
		} else {
			echo "<ul>\n";
			foreach ($missing as $name) {
				echo "<li>{$name}</li>\n";
			}
			echo "</ul>\n";
		}
		echo "</div>\n";
		
		// Lost Ones
		echo "<div class=\"box alignright\" style=\"width: 45%\">\n";
		echo "<p><strong>Lost Ones</strong></p>\n";
		echo "<p>The following sub-pages are no longer Residents (i.e. they should be moved to the Archives), or are missing the 'username' custom field:</p>\n";
		if (!$lostones) {
			echo "<p>None.</p>\n";
		} else {
			echo "<ul>\n";
			foreach ($lostones as $child) {
				$title = get_the_title($child->ID);
				$link = get_permalink($child->ID);
				echo "<li><a href=\"{$link}\" title=\"{$title}\">{$title}</a></li>\n";
			}
			echo "</ul>\n";
		}
		echo "</div>\n";
		
		echo "<div class=\"clear\"></div>\n";
	}
	
} catch (Exception $e) {
	echo "<p><strong>ERROR:</strong> {$e->getMessage()}</p>\n";
}
?>
    </div>
    
<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
<?php endif; ?>