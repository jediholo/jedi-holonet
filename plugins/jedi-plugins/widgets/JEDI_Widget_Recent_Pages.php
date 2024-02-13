<?php
class JEDI_Widget_Recent_Pages extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'jedi_widget_recent_pages', 'description' => __('The most recent pages') );
		parent::__construct('jwidget_recent_pages', __('JEDI: Recent Pages'), $widget_ops);
	}

	public function widget($args, $instance) {
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Pages') : $instance['title'], $instance, $this->id_base);
		$parent = empty($instance['parent']) ? '' : jedi_get_page_id_by_name($instance['parent']);
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
		usort($r, function($a, $b) { return strcmp($b->post_modified, $a->post_modified); });

		if (count($r) > 0) :
			global $post;

			echo $args['before_widget'];
			if ( !empty( $title ) ) { echo $args['before_title'] . $title . $args['after_title']; }
			echo "<ul>\n";

			for ($i = 0; $i < min($number, count($r)); $i++) : $post = $r[$i]; setup_postdata($post);
?><li><?php if ($instance['show_date']) : ?><span title="<?php the_modified_date('Y-m-d'); ?>"><?php the_modified_date(); ?></span>: <?php endif; ?>
<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo jedi_get_the_title_ellipsis($maxlength); ?></a></li><?php
			endfor;

			echo "</ul>\n";
			echo $args['after_widget'];

			wp_reset_query();
		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['parent'] = strip_tags($new_instance['parent']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset($new_instance['show_date']);
		$instance['maxlength'] = intval($new_instance['maxlength']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'parent' => '', 'number' => '5', 'maxlength' => '' ) );
		$title = strip_tags($instance['title']);
		$parent = strip_tags($instance['parent']);
		$number = intval($instance['number']);
		$maxlength = intval($instance['maxlength']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('parent'); ?>"><?php _e('Parent page name (slug):'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('parent'); ?>" name="<?php echo $this->get_field_name('parent'); ?>" type="text" value="<?php echo $parent; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />

		<p><input id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" type="checkbox" <?php checked($instance['show_date']); ?> />&nbsp;<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?'); ?></label></p>

		<p><label for="<?php echo $this->get_field_id('maxlength'); ?>"><?php _e('Maximum title length:'); ?></label>
		<input id="<?php echo $this->get_field_id('maxlength'); ?>" name="<?php echo $this->get_field_name('maxlength'); ?>" type="text" value="<?php echo $maxlength; ?>" size="3" /></p>
<?php
	}

}
