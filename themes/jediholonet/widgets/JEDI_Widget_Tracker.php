<?php
class JEDI_Widget_Tracker extends WP_Widget {

	function JEDI_Widget_Tracker() {
		$widget_ops = array('classname' => 'widget_tracker', 'description' => __('Q3 Server Tracker using AJAX'));
		$this->WP_Widget('jwidget_tracker', __('JEDI: Server Tracker'), $widget_ops);
	}
	
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		echo "<ul><li><dl><dt>IP: </dt><dd>" . $instance['server'] . "</dd></dl></li></ul>\n";
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['server'] = strip_tags($new_instance['server']);
		return $instance;
	}
	
	function form( $instance ) {
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

register_widget('JEDI_Widget_Tracker');
