<?php if (!isAjax()) : ?>
<?php get_header(); ?>

    <!-- Content header bar -->
    <div id="contentHeader">
      <!-- Page title -->
      <h2><?php echo get_full_title(true); ?></h2>
      
      <!-- Navigation -->
      <div class="navigation">
        <ul>
<?php $nextposts = get_next_posts_link('&laquo; Older Entries'); if ($nextposts) echo "          <li>{$nextposts}</li>\n"; ?>
<?php $prevposts = get_previous_posts_link('Newer Entries &raquo;'); if ($prevposts) echo "          <li>{$prevposts}</li>\n"; ?>
        </ul>
      </div>

      <div class="clear"></div>
    </div>

<?php else : ?>
  <!-- Actual content -->
  <div class="content">
<?php endif; ?>

<?php get_sidebar(); ?>

    <div class="narrowcolumn" id="narrowcolumn-<?php echo get_root_name(); ?>">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <!-- Post #<?php the_ID(); ?> -->
    <div class="box">
      <div class="post" id="post-<?php the_ID(); ?>">
        <h4>
          <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
            <?php if ($post->post_type != 'page') : ?>
            <span title="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></span> //
            <?php endif; ?>
            <?php the_title(); ?>
          </a>
        </h4>

        <ul class="post-categories">
          <?php foreach (get_the_category() as $category) : ?>
            <li><a href="<?php echo get_category_link( $category->term_id ) ?>" title="<?php echo esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) ?>" class="post-category post-category-<?php echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
          <?php endforeach; ?>
        </ul>

        <div class="entry">
<?php the_excerpt(); ?>
        </div>
        <p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> <?php edit_post_link('Edit', ' | ', ''); ?></p>
      </div>
    </div>

<?php endwhile; ?>

<?php else : ?>
    <div class="box">
      <p>Sorry, but you are looking for something that isn't here.</p>
<?php get_search_form(); ?>
    </div>
<?php endif; ?>

    </div>

<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
<?php endif; ?>