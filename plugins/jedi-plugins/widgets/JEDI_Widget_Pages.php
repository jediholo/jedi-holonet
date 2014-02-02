<?php
class JEDI_Widget_Pages extends WP_Widget_Pages {

	function JEDI_Widget_Pages() {
		$widget_ops = array('classname' => 'widget_pages', 'description' => __( 'Your blog&#8217;s WordPress Pages') );
		$this->WP_Widget('jwidget_pages', __('JEDI: Pages'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title']);
		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];
		$parent = empty($instance['parent']) ? '' : get_page_id_by_name($instance['parent']);
		$depth = intval($instance['depth']);
		if ($depth < 0) $depth = 0;

		if ( $sortby == 'menu_order' )
			$sortby = 'menu_order, post_title';

		$out = wp_list_pages(array(
			'title_li' => '',
			'echo' => 0,
			'sort_column' => $sortby,
			'exclude' => $exclude,
			'child_of' => $parent,
			'depth' => $depth,
		));

		if ( !empty( $out ) ) {
			echo $before_widget;
			if ($title) echo $before_title . $title . $after_title;
			echo "<ul>\n" . $out . "</ul>\n";
			echo $after_widget;
		}
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = parent::update($new_instance, $old_instance);
		$instance['parent'] = strip_tags($new_instance['parent']);
		$instance['depth'] = intval($new_instance['depth']);
		return $instance;
	}
	
	function form( $instance ) {
		parent::form($instance);
		$parent = strip_tags($instance['parent']);
		$depth = intval($instance['depth']);
?>
		<p><label for="<?php echo $this->get_field_id('parent'); ?>"><?php _e('Parent page name (slug):'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('parent'); ?>" name="<?php echo $this->get_field_name('parent'); ?>" type="text" value="<?php echo $parent; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('Depth:'); ?></label>
		<input id="<?php echo $this->get_field_id('depth'); ?>" name="<?php echo $this->get_field_name('depth'); ?>" type="text" value="<?php echo $depth; ?>" size="3" /></p>
<?php
	}
}
