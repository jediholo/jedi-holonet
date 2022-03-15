<?php
/*
Template Name: Showcase
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="description" content="JEDI is a thriving Star Wars role-playing community offering a complete Jedi curriculum to its members, in an immersive universe set after 250 ABY." />
<title><?php echo get_full_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/showcase.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/apple-touch-icon.png" />
<link rel="icon" type="image/png" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.png" />
<?php wp_head(); ?>
</head>

<body<?php if ($style = get_post_meta($post->ID, 'body_style', true)) echo " style=\"$style\"" ?>>

<div class="showcase">
<p style="text-align: center;"><a href="<?php echo get_posts_url(); ?>">Proceed to <?php echo get_bloginfo('blogname'); ?> &raquo;</a></p>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="showcase-content" id="post-<?php the_ID(); ?>">
<?php the_content('Read the rest of this entry &raquo;'); ?>
</div>

<?php endwhile; endif; ?>

<p style="text-align: center;"><a href="<?php echo get_posts_url(); ?>">Proceed to <?php echo get_bloginfo('blogname'); ?> &raquo;</a></p>
</div>

<?php wp_footer(); ?>
</body>
</html>