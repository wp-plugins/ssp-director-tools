<?php

function sspdt_options_page() {
	
	?>
	
	<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('SlideShowPro Director Tools Options', 'sspdt'); ?></h2>
	
	<form action="options.php" method="post">
	<?php settings_fields('sspdt_api_options'); ?>
	<?php do_settings_sections('add_sspdt_options_page')?>
	</form>
	
	<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php _e('Update settings', 'sspdt'); ?>" /></p>
	
	</div>
	
	<?php 
} //end function sspdt-options-page


// Register and define the settings
add_action( 'admin_init', 'sspdt_register_settings');
function sspdt_register_settings() {
	register_setting( 'sspdt_api_options', 'sspdt_api_options', 'sspdt_validate_options');
	add_settings_section( 'sspdt_api_section', __('API Information', 'sspdt'), 'sspdt_api_settings_header', 'add_sspdt_options_page');
	add_settings_field( 'sspdt_api_key_field', __('API key', 'sspdt'), 'sspdt_api_key_input', 'add_sspdt_options_page', 'sspdt_api_section');
	add_settings_field( 'sspdt_api_path_field', __('API path', 'sspdt'), 'sspdt_api_path_input', 'add_sspdt_options_page', 'sspdt_api_section');
	add_settings_field( 'sspdt_api_cache_field', __('API cache', 'sspdt'), 'sspdt_api_cache_input', 'add_sspdt_options_page', 'sspdt_api_section');
	
	add_settings_section( 'sspdt_image_section', __('Image Sizes and Handling', 'sspdt'), 'sspdt_image_settings_header', 'add_sspdt_options_page');
	
	add_settings_section( 'sspdt_presentation_section', __('Image Presentation', 'sspdt'), 'sspdt_presentation_settings_header', 'add_sspdt_options_page');
}


//Draw section headers
function sspdt_api_settings_header() {
	printf("<p><i>%s</i><p>", __('Enter SSP Director API key and path info here. You can find these values on the "System Info" page of your Director installation.', 'sspdt'));
}

function sspdt_image_settings_header() {
	printf("<p><i>%s</i></p>", __('Define image sizes and how images should fit into.', 'sspdt') );
}

function sspdt_presentation_settings_header() {
	printf("<p><i>%s</i></p>", __('Select a tool for image presentation.', 'sspdt') );
}

//Draw form input fields
function sspdt_api_key_input() {
	$options = get_option ( 'sspdt_api_options' );
	$api_key = $options['api_key'];
	
	echo "<input id='api_key' name='sspdt_api_options[api_key]' type='text' value='$api_key' size='60' />";
}

function sspdt_api_path_input() {
	$options = get_option ( 'sspdt_api_options' );
	$api_path = $options['api_path'];
	
	echo "<input id='api_path' name='sspdt_api_options[api_path]' type='text' value='$api_path' size='60' />";
}

function sspdt_api_cache_input() {
	$options = get_option ( 'sspdt_api_options' );
	$api_cache = $options['api_cache'];
	
	if (!is_writable ( realpath(dirname(__FILE__)) . '/api/cache' )) { 
		$disable_cache = 1;
		$options['api_cache'] = 0;
		update_option( 'sspdt_api_options', $options);
		printf( __( "<div class='updated fade'>Your cache directory is not writable. You can't activate caching unless you allow your webserver to write to <code>'wp-content/plugins/%s/api/cache'</code>.</div>", "sspdt" ) , plugin_basename(dirname(__FILE__)) );
	}

	echo "<input id='api_cache' name='sspdt_api_options[api_cache]' type='radio' value='1' checked='$api_cache' ";
	if($disable_cache == 1) echo 'disabled';
	echo "/> ";
	_e('Enabled', 'sspdt');
	echo "<br />";
	echo "<input id='api_cache' name='sspdt_api_options[api_cache]' type='radio' value='0' checked='$api_cache' ";
	if($disable_cache == 1) echo 'disabled'; 
	echo "/> ";
	_e('Disabled', 'sspdt');


}


//Validate user input
function sspdt_validate_options( $input ) {
	$valid = array();
	
	return $valid;
}


?>