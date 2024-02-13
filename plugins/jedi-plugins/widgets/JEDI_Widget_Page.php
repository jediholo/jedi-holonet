<?php
class JEDI_Widget_Page extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'jedi_widget_page', 'description' => __('Display a page as a widget'));
		parent::__construct('jwidget_page', __('JEDI: Page'), $widget_ops);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$page = empty($instance['page_path']) ? null : get_page_by_path($instance['page_path']);

		echo $args['before_widget'];
		if ( !empty( $title ) ) { echo $args['before_title'] . $title . $args['after_title']; }
		if ( !empty( $page ) ) { echo apply_filters('the_content', $page->post_content); }
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_path'] = strip_tags($new_instance['page_path']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'page_path' => '' ) );
		$title = strip_tags($instance['title']);
		$page_path = strip_tags($instance['page_path']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('page_path'); ?>"><?php _e('Page path:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('page_path'); ?>" name="<?php echo $this->get_field_name('page_path'); ?>" type="text" value="<?php echo esc_attr($page_path); ?>" /></p>
<?php
	}

}
