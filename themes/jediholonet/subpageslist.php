<?php
/*
Template Name: Sub-pages list
*/
require_once(TEMPLATEPATH . '/include/IconList.class.php');
?>
<?php if (!isAjax()) : ?>
<?php get_header(); ?>

    <!-- Content header bar -->
    <div id="contentHeader">
      <!-- Page title -->
      <h2><?php echo get_full_title(true); ?></h2>

<?php $show_nav = strtolower(trim(get_post_meta($post->ID, 'show_nav', true)));
if (!in_array($show_nav, array('no', 'false', '0'))) : ?>
      <!-- Navigation -->
      <div class="navigation">
        <ul>
<?php wp_list_pages('title_li=&depth=1&child_of='.get_root_id()); ?>
        </ul>
      </div>
<?php endif; ?>

      <div class="clear"></div>
    </div>

<?php else : ?>
  <!-- Actual content -->
  <div class="content">
<?php endif; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <!-- Post #<?php the_ID(); ?> -->
    <div class="post" id="post-<?php the_ID(); ?>">
      <div class="entry">
<?php $content = get_the_content(); if (!empty($content)) : ?>
<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
<?php endif;?>

<?php
$group_by = get_post_meta($post->ID, 'group_by', true);
$order_by = get_post_meta($post->ID, 'order_by', true);
$sort_column = get_post_meta($post->ID, 'sort_column', true);
$sort_order = get_post_meta($post->ID, 'sort_order', true);
$subtitle1_key = get_post_meta($post->ID, 'subtitle1_key', true);
$subtitle2_key = get_post_meta($post->ID, 'subtitle2_key', true);
$default_group = get_post_meta($post->ID, 'default_group', true);
if (empty($default_group)) $default_group = 'Uncategorized items';
$collapsable_groups = strtolower(trim(get_post_meta($post->ID, 'collapsable_groups', true)));
$collapsable_groups = in_array($collapsable_groups, array('yes', 'true', '1'));
$boxed_links = strtolower(trim(get_post_meta($post->ID, 'boxed_links', true)));
$boxed_links = !in_array($boxed_links, array('no', 'false', '0'));
$auto_plural = strtolower(trim(get_post_meta($post->ID, 'auto_plural', true)));
$auto_plural = in_array($auto_plural, array('yes', 'true', '1'));

$args = array('child_of' => $post->ID);
if (!empty($sort_column)) $args['sort_column'] = $sort_column;
if (!empty($sort_order)) $args['sort_order'] = $sort_order;
$children = get_pages($args);

if (!empty($group_by)) {
	// Prepare the groups in the order given
	$groups = array();
	if (!empty($order_by)) {
		$order_by = json_decode($order_by);
		foreach ($order_by as $group) {
			$groups[$group] = array();
		}
	}
	$groups[$default_group] = array();
	
	// Group sub-pages by a given meta key
	foreach ($children as $child) {
		$group_name = get_post_meta($child->ID, $group_by, true);
		if (empty($group_name)) $group_name = $default_group;
		$groups[$group_name][] = $child;
	}
	
	// Print each group
	$groupId = 1;
	foreach ($groups as $group_name => $children) {
		if (count($children) == 0) continue;
		
		// Print the header
		echo "<div class=\"groupContainer" . ($collapsable_groups ? ' collapsableGroup' : '') . "\">\n";
		echo "<h3 class=\"groupHeader\">{$header_start}{$group_name}" . ($auto_plural ? 's' : '') . "{$header_end}</h3>\n";
		echo "<div class=\"groupContent\">\n";
		
		// Create the list
		$list = new IconList();
		foreach ($children as $child) {
			// Create and add the item to the list
			$item = new IconItem(get_the_title($child->ID));
			$item->setGroup($group_name);
			$item->setLink(get_permalink($child->ID));
			$boxed = !in_array(get_post_meta($child->ID, 'boxed', true), array('no', 'false', '0'));
			$item->setBoxed($boxed_links && $boxed);
			if (!empty($subtitle1_key)) $item->setSubtitle1(get_post_meta($child->ID, $subtitle1_key, true));
			if (!empty($subtitle2_key)) $item->setSubtitle2(get_post_meta($child->ID, $subtitle2_key, true));
			$item->setIcon(get_post_meta($child->ID, 'icon', true));
			$list->addItem($item);
		}
		
		// Print the list
		echo $list;
		echo "</div>\n";
		echo "</div>\n\n";
		
		$groupId++;
	}
	
} else {
	// Create the list
	$list = new IconList();
	foreach ($children as $child) {
		// Create and add the item to the list
		$item = new IconItem(get_the_title($child->ID));
		$item->setLink(get_permalink($child->ID));
		$boxed = !in_array(get_post_meta($child->ID, 'boxed', true), array('no', 'false', '0'));
		$item->setBoxed($boxed_links && $boxed);
		if (!empty($subtitle1_key)) $item->setSubtitle1(get_post_meta($child->ID, $subtitle1_key, true));
		if (!empty($subtitle2_key)) $item->setSubtitle2(get_post_meta($child->ID, $subtitle2_key, true));
		$item->setIcon(get_post_meta($child->ID, 'icon', true));
		$list->addItem($item);
	}
	
	// Print the list
	echo $list;
}
?>
      </div>
    </div>
<?php endwhile; endif; ?>


    <?php edit_post_link('Edit this page', '<p class="postmetadata">', '</p>'); ?>

<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
<?php endif; ?>