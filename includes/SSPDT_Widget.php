<?php

require_once 'SSPDT.php';

/**
 * Our widget class extending WP_Widget class
 * @author Matthias Scheidl <dev@scheidl.name>
 *
 */
class SSPDT_Widget extends WP_Widget {


	public function __construct() {
		$widget_ops = array(
			'classname'      => 'SSPDT_Widget', 
			'description'    => __('Displays a photogrid populated with SSP Director content.', 'sspdt')
		);
		$control_ops = array (
			'width'  => '300',
			'height' => '200'
			);
		
		parent::__construct(false, $name = __('Director Photogrid', 'sspdt'), $widget_options = $widget_ops, $control_options = $control_ops);
	}


	/**
	 * Create the widget form
	 * @param array $instance Our instance variables
	 * @return void
	 */
	function form($instance) {
		$defaults = array(
        	'title'            => __('Title', 'sspdt'), 
        	'model'            => 'album', 
        	'model_id'         => '0', 
        	'limit'            => '8', 
        	'tags'             => '', 
        	'tagmode'          => 'all', 
        	'sort_on'          => 'captured_on', 
        	'sort_direction'   => 'DESC',
        	'element'          => 'h3'
        	);

        	$instance          = wp_parse_args( (array) $instance, $defaults );
        	$title             = $instance['title'];
        	$model             = $instance['model'];
        	$model_id          = $instance['model_id'];
        	$limit             = $instance['limit'];
        	$tags              = $instance['tags'];
        	$tagmode           = $instance['tagmode'];
        	$sort_on           = $instance['sort_on'];
        	$sort_direction    = $instance['sort_direction'];
        	$element           = $instance['element'];

        	?>
<p>
<?php  _e('Title', 'sspdt'); ?>
	<input class="widefat"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<?php _e('Surrounding HTML Element', 'sspdt'); ?>
	<select name="<?php echo $this->get_field_name( 'element' ); ?>"
		style="min-width: 60px;">
		<option value="h2"
		<?php if(esc_attr($element) == 'h2') {echo "selected"; } ?>>h2</option>
		<option value="h3"
		<?php if(esc_attr($element) == 'h3') {echo "selected"; } ?>>h3</option>
		<option value="p"
		<?php if(esc_attr($element) == 'p') {echo "selected"; } ?>>p</option>
	</select>
</p>
<p>
<?php  _e('Scope', 'sspdt'); ?>
	<br /> <select name="<?php echo $this->get_field_name( 'model' ); ?>"
		onchange="model_on_change();">
		<option value="null"
		<?php if(esc_attr($model) == 'null') {echo "selected";}?>>
			<?php _e('-- Not specified --', 'sspdt'); ?>
		</option>
		<option value="gallery"
		<?php if(esc_attr($model) == 'gallery') {echo "selected";}?>>
			<?php _e('Gallery', 'sspdt'); ?>
		</option>
		<option value="album"
		<?php if(esc_attr($model) == 'album') {echo "selected";}?>>
			<?php _e('Album', 'sspdt'); ?>
		</option>
	</select> ID <input
		name="<?php echo $this->get_field_name( 'model_id' ); ?>" size="3"
		type="text" value="<?php echo esc_attr( $model_id ); ?>" />
</p>
<p>
<?php  printf(__('Limit to <input name="%s" type="text" size="3" value="%s"/> images.', 'sspdt'), esc_attr($this->get_field_name( 'limit' )), $limit); ?>
</p>
<p>
<?php  _e('Tags', 'sspdt'); ?>
	<input name="<?php echo $this->get_field_name('tags'); ?>" type="text"
		value="<?php echo esc_attr($tags); ?>" /> <select
		name="<?php echo $this->get_field_name( 'tagmode' ); ?>">
		<option value="all"
		<?php if(esc_attr($tagmode) == 'all') {echo "selected";}?>>
			<?php _e('all', 'sspdt'); ?>
		</option>
		<option value="one"
		<?php if(esc_attr($tagmode) == 'null') {echo "selected";}?>>
			<?php _e('one', 'sspdt'); ?>
		</option>
	</select>
</p>
<p>
<?php  _e('Sort by', 'sspdt'); ?>
	<select id="sort_on"
		name="<?php echo $this->get_field_name( 'sort_on' ); ?>"
		style="min-width: 120px;" onchange="sort_on_change();">
		<option value="null"
		<?php if($sort_on == 'null') {echo "selected";} ?>>
			<?php _e('-- Not specified --', 'sspdt'); ?>
		</option>
		<option value="created_on"
		<?php if($sort_on == 'created_on') {echo "selected";} ?>>
			<?php _e('Creation Date', 'sspdt'); ?>
		</option>
		<option value="captured_on"
		<?php if($sort_on == 'captured_on') {echo "selected";} ?>>
			<?php _e('Capture Date', 'sspdt'); ?>
		</option>
		<option value="modified_on"
		<?php if($sort_on == 'modified_on') {echo "selected";} ?>>
			<?php _e('Modification Date', 'sspdt'); ?>
		</option>
		<option value="filename"
		<?php if($sort_on == 'filename') {echo "selected";} ?>>
			<?php _e('File Name', 'sspdt'); ?>
		</option>
		<option value="random"
		<?php if($sort_on == 'random') {echo "selected";} ?>>
			<?php _e('Random', 'sspdt'); ?>
		</option>
	</select> <select id="sort_direction"
		name="<?php echo $this->get_field_name( 'sort_direction' ); ?>"
		style="min-width: 80px;"
			<?php if($sort_on == 'null' || $sort_on == null || $sort_on == 'random') {echo "disabled";} ?>>
		<option value="ASC"
		<?php if($sort_direction == 'ASC') {echo "selected";} ?>>
			<?php _e('ascending', 'sspdt'); ?>
		</option>
		<option value="DESC"
		<?php if($sort_direction == 'DESC') {echo "selected";} ?>>
			<?php _e('descending', 'sspdt'); ?>
		</option>
	</select>
</p>

			<?php
	}


	/**
	 *	Update the widget
	 *	@param array $new_instance The new instance variables
	 *	@param array $old_instance The old instance varaiables
	 *	@return array $instance The new instance variables returned
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title']            = strip_tags( $new_instance['title'] );
		$instance['model']            = strip_tags( $new_instance['model'] );
		$instance['model_id']         = strip_tags( $new_instance['model_id'] );
		$instance['limit']            = strip_tags( $new_instance['limit'] );
		$instance['tags']             = strip_tags( $new_instance['tags'] );
		$instance['tagmode']          = strip_tags( $new_instance['tagmode'] );
		$instance['sort_on']          = strip_tags( $new_instance['sort_on'] );
		$instance['sort_direction']   = strip_tags( $new_instance['sort_direction'] );
		$instance['element']          = strip_tags( $new_instance['element'] );
		return $instance;
	}



	/**
	 *	Display the widget in the frontend
	 *	@param array $args
	 *	@param array $instance
	 *	@return void
	 */
	function widget( $args, $instance ) {
		extract($args);

		$sspdt = new SSPDT(get_option('sspdt_api_key'), get_option('sspdt_api_path'), true, get_option('sspdt_format_options'));
		
		if(get_option('sspdt_api_cache')) {
			$sspdt->cache->set('sspdt');
		}

		$title                = apply_filters( 'widget_title', $instance['title'] );
		$elem                 = $instance['element'];

		$presentation_ops     = get_option('sspdt_helpers');
		$presentation_helper  = $presentation_ops['presentation_helper'];
		$helper               = array('class' => $presentation_helper );

		echo $before_widget;
		echo "<$elem class='widget-title'>$title</$elem>";
		echo $sspdt->photogrid($instance, 'widget');
		echo $after_widget;
	}

}

?>