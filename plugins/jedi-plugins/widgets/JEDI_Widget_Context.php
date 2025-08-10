<?php
class JEDI_Widget_Context extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'jedi_widget_context', 'description' => __('Page context message (search, archives, category, etc.)'));
		parent::__construct('jwidget_context', __('JEDI: Context'), $widget_ops);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];

		if (is_category()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews Archives for the <strong><?php single_cat_title(''); ?></strong> category.</p>
<?php	} elseif (is_day()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews archives for <strong><span title="<?php the_time('F j, Y'); ?>"><?php the_time(get_option('date_format')); ?></span></strong>.</p>
<?php	} elseif (is_month()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews archives for the month <strong><?php the_time('F, Y'); ?></strong>.</p>
<?php	} elseif (is_year()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews archives for the year <strong><?php the_time('Y'); ?></strong>.</p>
<?php	} elseif (is_search()) { ?>
			<p>You have searched the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews archives for <strong>'<?php the_search_query(); ?>'</strong>. If you are unable to find anything in these search results, you can try one of the links below.</p>
<?php	} elseif (is_paged()) { ?>
			<p>You are currently browsing the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> HoloNews archives.</p>
<?php	}

		echo $args['after_widget'];
	}

}
