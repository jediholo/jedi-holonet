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
<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
      </div>
    </div>
<?php endwhile; endif; ?>

    <?php edit_post_link('Edit this page', '<p class="postmetadata">', '</p>'); ?>

<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
<?php endif; ?>