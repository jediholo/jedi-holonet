<?php
class JEDI_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	public function __construct() {
		parent::__construct();
		$this->id_base = 'jwidget_recent_posts';
		$this->name = __('JEDI: Recent Posts');
		$this->option_name = 'widget_' . $this->id_base;
		$this->widget_options['classname'] = 'jedi_widget_recent_entries';
		$this->control_options['id_base'] = $this->id_base;
	}

	public function widget($args, $instance) {
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		$maxlength = intval($instance['maxlength']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
			echo $args['before_widget'];
			if ( $title ) echo $args['before_title'] . $title . $args['after_title'];
			echo "<ul>\n";

			while ($r->have_posts()) : $r->the_post();
?><li><?php if ($instance['show_date']) : ?><span title="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></span>: <?php endif; ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo jedi_get_the_title_ellipsis($maxlength); ?></a></li><?php
			endwhile;

			echo "</ul>\n";
			echo $args['after_widget'];

			wp_reset_query();
		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = parent::update($new_instance, $old_instance);
		$instance['maxlength'] = intval($new_instance['maxlength']);
		return $instance;
	}

	public function form( $instance ) {
		parent::form($instance);
		$maxlength = intval($instance['maxlength']);
?>
		<p><label for="<?php echo $this->get_field_id('maxlength'); ?>"><?php _e('Maximum title length:'); ?></label>
		<input id="<?php echo $this->get_field_id('maxlength'); ?>" name="<?php echo $this->get_field_name('maxlength'); ?>" type="text" value="<?php echo $maxlength; ?>" size="3" /></p>
<?php
	}

}
