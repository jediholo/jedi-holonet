<?php
class JEDI_Widget_Recent_Pages extends WP_Widget {

	function JEDI_Widget_Recent_Pages() {
		$widget_ops = array('classname' => 'widget_recent_pages', 'description' => __( "The most recent pages") );
		$this->WP_Widget('jwidget_recent_pages', __('JEDI: Recent Pages'), $widget_ops);
		
		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_pages', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) )
			return $cache[$args['widget_id']];

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Pages') : $instance['title']);
		$parent = empty($instance['parent']) ? '' : get_page_id_by_name($instance['parent']);
		$maxlength = intval($instance['maxlength']);
		if ( !$number = (int) $instance['number'] )
			$number = 5;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		$r = get_pages(array(
			'sort_column' => 'post_modified',
			'sort_order' => 'desc',
			'child_of' => $parent,
		));
		usort($r, create_function('$a, $b', 'return strcmp($b->post_modified, $a->post_modified);'));
		
		if (count($r) > 0) :
			global $post;
			
			echo $before_widget;
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
			echo "<ul>\n";
			
			for ($i = 0; $i < $number; $i++) : $post = $r[$i]; setup_postdata($post);
?><li><?php if ($instance['display_date']) : ?><span title="<?php the_modified_date(); ?>"><?php the_modified_date('J.d'); ?></span>: <?php endif; ?>
<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo get_the_title_ellipsis($maxlength); ?></a></li><?php
			endfor;
			
			echo "</ul>\n";
			echo $after_widget;
			
			wp_reset_query();
		endif;
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_pages', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['parent'] = strip_tags($new_instance['parent']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['display_date'] = isset($new_instance['display_date']);
		$instance['maxlength'] = intval($new_instance['maxlength']);
		$this->flush_widget_cache();
		return $instance;
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_recent_pages', 'widget');
	}

	function form( $instance ) {
		$title = strip_tags($instance['title']);
		$parent = strip_tags($instance['parent']);
		if ( !$number = (int) $instance['number'] )
			$number = 5;
		$maxlength = intval($instance['maxlength']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('parent'); ?>"><?php _e('Parent page name (slug):'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('parent'); ?>" name="<?php echo $this->get_field_name('parent'); ?>" type="text" value="<?php echo $parent; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)'); ?></small></p>
		
		<p><label for="<?php echo $this->get_field_id('maxlength'); ?>"><?php _e('Maximum title length:'); ?></label>
		<input id="<?php echo $this->get_field_id('maxlength'); ?>" name="<?php echo $this->get_field_name('maxlength'); ?>" type="text" value="<?php echo $maxlength; ?>" size="3" /></p>
		
		<p><input id="<?php echo $this->get_field_id('display_date'); ?>" name="<?php echo $this->get_field_name('display_date'); ?>" type="checkbox" <?php checked($instance['display_date']); ?> />&nbsp;<label for="<?php echo $this->get_field_id('display_date'); ?>"><?php _e('Display page date'); ?></label></p>
<?php
	}
}

register_widget('JEDI_Widget_Recent_Pages');
