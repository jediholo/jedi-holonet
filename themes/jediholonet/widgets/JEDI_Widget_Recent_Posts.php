<?php
class JEDI_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	function JEDI_Widget_Recent_Posts() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your blog") );
		$this->WP_Widget('jwidget_recent_posts', __('JEDI: Recent Posts'), $widget_ops);

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}
	
	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) )
			return $cache[$args['widget_id']];

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title']);
		$maxlength = intval($instance['maxlength']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;
			echo "<ul>\n";
			
			while ($r->have_posts()) : $r->the_post();
?><li><?php if ($instance['display_date']) : ?><span title="<?php the_time(get_option('date_format')); ?>"><?php the_time('J.d'); ?></span>: <?php endif; ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo get_the_title_ellipsis($maxlength); ?></a></li><?php
			endwhile;
			
			echo "</ul>\n";
			echo $after_widget;
			
			wp_reset_query();  // Restore global post data stomped by the_post().
		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_recent_posts', $cache, 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = parent::update($new_instance, $old_instance);
		$instance['display_date'] = isset($new_instance['display_date']);
		$instance['maxlength'] = intval($new_instance['maxlength']);
		return $instance;
	}
	
	function form( $instance ) {
		parent::form($instance);
		$maxlength = intval($instance['maxlength']);
?>
		<p><label for="<?php echo $this->get_field_id('maxlength'); ?>"><?php _e('Maximum title length:'); ?></label>
		<input id="<?php echo $this->get_field_id('maxlength'); ?>" name="<?php echo $this->get_field_name('maxlength'); ?>" type="text" value="<?php echo $maxlength; ?>" size="3" /></p>
		
		<p><input id="<?php echo $this->get_field_id('display_date'); ?>" name="<?php echo $this->get_field_name('display_date'); ?>" type="checkbox" <?php checked($instance['display_date']); ?> />&nbsp;<label for="<?php echo $this->get_field_id('display_date'); ?>"><?php _e('Display page date'); ?></label></p>
<?php
	}
}

register_widget('JEDI_Widget_Recent_Posts');
