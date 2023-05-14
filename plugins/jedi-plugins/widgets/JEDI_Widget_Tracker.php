<?php
class JEDI_Widget_Tracker extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'jedi_widget_tracker', 'description' => __('Server Tracker using AJAX'));
		parent::__construct('jwidget_tracker', __('JEDI: Server Tracker'), $widget_ops);
	}

	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		echo "<ul><li><dl><dt>Server: </dt><dd>" . $instance['server'] . "</dd></dl></li></ul>\n";
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['server'] = strip_tags($new_instance['server']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'server' => '' ) );
		$title = strip_tags($instance['title']);
		$server = strip_tags($instance['server']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('server'); ?>"><?php _e('Server:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('server'); ?>" name="<?php echo $this->get_field_name('server'); ?>" type="text" value="<?php echo esc_attr($server); ?>" /></p>
<?php
	}
}
