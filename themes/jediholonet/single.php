<?php if (!isAjax()) : ?>
<?php get_header(); ?>

    <!-- Content header bar -->
    <div id="contentHeader">
      <!-- Page title -->
      <h2><?php echo get_full_title(true); ?></h2>
      
      <!-- Navigation -->
      <div class="navigation">
        <ul>
          <?php previous_post_link('&laquo; %link', '<span title="%title">Previous article</span>'); ?>
          <?php next_post_link('%link &raquo;', '<span title="%title">Next article</span>'); ?>
        </ul>
      </div>

      <div class="clear"></div>
    </div>

<?php else : ?>
  <!-- Actual content -->
  <div class="content">
<?php endif; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <!-- Post #<?php the_ID(); ?> -->
    <div class="box">
      <div class="post" id="post-<?php the_ID(); ?>">
        <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><span title="<?php the_time(get_option('date_format')); ?>"><?php the_time('J.d'); ?></span> // <?php the_title(); ?></a></h4>

        <ul class="post-categories">
          <?php foreach (get_the_category() as $category) : ?>
            <li><a href="<?php echo get_category_link( $category->term_id ) ?>" title="<?php echo esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) ?>" class="post-category post-category-<?php echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
          <?php endforeach; ?>
        </ul>

        <div class="entry">
<?php the_content('Read the rest of this entry &raquo;'); ?>
<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
          <div class="clear"></div>
        </div>
      </div>
    </div>
      
    <!-- Post metadata -->
    <div class="box">
<?php the_tags( '      <p class="postmetadata">Tags: ', ', ', '</p>'); ?>
      <p class="postmetadata">
        This entry was posted on <span title="<?php the_time(get_option('date_format')); ?>"><?php the_time('J.d'); ?></span> at <?php the_time(); ?> and is filed under <?php the_category(', '); ?>.

<?php if ('open' == $post-> comment_status) : ?>
        You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.
<?php endif; ?>
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
// Both Comments and Pings are open ?>
        You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.
<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
// Only Pings are Open ?>
        You can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.
<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
// Comments are open, Pings are not ?>
        You can skip to the end and leave a response.
<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
// Neither Comments, nor Pings are open ?>
<?php } ?>
      </p>
<?php edit_post_link('Edit this entry', '        <p class="postmetadata">', '</p>'); ?>
    </div>
<?php comments_template(); ?>

<?php endwhile; else: ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
<?php endif; ?>