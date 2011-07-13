<?php

function sspdt_options_page() {
	global $sspdt_nonce;

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?', 'ssspdt'));

		check_admin_referer( $sspdt_nonce );
		
		$director = new Director($_POST['sspdt_api_key'],$_POST['sspdt_api_path'],false);
		
		if ( !$director->dead == true ) {
				
			update_option( 'sspdt_api_key', $_POST['sspdt_api_key'] );
			update_option( 'sspdt_api_path', $_POST['sspdt_api_path'] );
			
			?><div id="message" class="updated fade"><p><strong><?php _e('Settings have been saved.', 'sspdt'); ?></strong></p></div><?php
			unset( $director );
			unset( $_POST['sspdt_api_key']);
			unset( $_POST['sspdt_api_key']);
			
		}
		
		else {
		
			?><div id="message" class="error"><p><strong><?php _e('API Settings seem wrong. Please double check them.', 'sspdt'); ?></strong></p></div>
		<?php
		}	
		
		update_option( 'sspdt_api_cache', $_POST['sspdt_api_cache'] );

		$format_options = array();
		
		$format_options['grid_width'] = sspdt_posint ( $_POST['grid_width'] );
		$format_options['grid_height'] = sspdt_posint ( $_POST['grid_height'] );
		$format_options['grid_crop'] = sspdt_bool( $_POST['grid_crop'] );
		$format_options['grid_quality'] = sspdt_posint ( $_POST['grid_quality'] );
		$format_options['grid_sharpen'] = sspdt_bool( $_POST['grid_sharpen'] );
		
		$format_options['thumb_width'] = sspdt_posint( $_POST['thumb_width'] );
		$format_options['thumb_height'] = sspdt_posint( $_POST['thumb_height'] );
		$format_options['thumb_crop'] = sspdt_bool( $_POST['thumb_crop'] );
		$format_options['thumb_quality'] = sspdt_posint( $_POST['thumb_quality'] );
		$format_options['thumb_sharpen'] = sspdt_bool( $_POST['thumb_sharpen'] );
		$format_options['thumb_align'] = sspdt_align( $_POST['thumb_align'] );
		$format_options['thumb_caption'] = sspdt_bool( $_POST['thumb_caption'] );
		
		$format_options['large_width'] = sspdt_posint( $_POST['large_width'] );
		$format_options['large_height'] = sspdt_posint( $_POST['large_height'] );
		$format_options['large_crop'] = sspdt_bool( $_POST['large_crop'] );
		$format_options['large_quality'] = sspdt_posint( $_POST['large_quality'] ); 
		$format_options['large_sharpen'] = sspdt_bool( $_POST['large_sharpen'] );
		//$format_options['large_caption'] = sspdt_bool( $_POST['large_caption'] );
		
		$defaults = array();
		
		$defaults['model'] = sspdt_model( $_POST['model'] );
		$defaults['model_id'] = sspdt_posint( $_POST['model_id'] );
		$defaults['limit'] = sspdt_posint( $_POST['limit'] );
		$defaults['tags'] = sspdt_nohtml( $_POST['tags'] );
		$defaults['tagmode'] = sspdt_tagmode( $_POST['tagmode'] );
		$defaults['sort_on'] = sspdt_sort_on( $_POST['sort_on'] );
		$defaults['sort_direction'] = sspdt_sort_direction( $_POST['sort_direction'] );
		$defaults['rss'] = sspdt_bool( $_POST['rss'] );
		
		$fb = array();
		
		$fb['padding'] = sspdt_posint( $_POST['padding'] );
		$fb['margin'] = sspdt_posint( $_POST['margin'] );
		
		$fb['titleShow'] = sspdt_bool( $_POST['titleShow'] );
		$fb['titlePosition'] = sspdt_fb_title_position( $_POST['titlePosition'] );
		$fb['counterShow'] = sspdt_bool( $_POST['counterShow'] );
		
		$fb['overlayShow'] = sspdt_bool( $_POST['overlayShow'] );
		$fb['overlayOpacity'] = sspdt_fb_opacity( $_POST['overlayOpacity'] );
		$fb['overlayColor'] = sspdt_color( $_POST['overlayColor'] );
		
		$fb['cyclic'] = sspdt_bool( $_POST['cyclic'] );
		$fb['showNavArrows'] = sspdt_bool( $_POST['showNavArrows'] );
		$fb['showCloseButton'] = sspdt_bool( $_POST['showCloseButton'] );
		$fb['enableEscapeButton'] = sspdt_bool( $_POST['enableEscapeButton'] );
		
		$fb['transitionIn'] = sspdt_fb_transition( $_POST['transitionIn'] );
		$fb['speedIn'] = sspdt_posint( $_POST['speedIn'] );
		$fb['easingIn'] = sspdt_fb_easing( $_POST['easingIn'] );
		$fb['transitionOut'] = sspdt_fb_transition( $_POST['transitionOut'] );
		$fb['speedOut'] = sspdt_posint( $_POST['speedOut'] );
		$fb['easingOut'] = sspdt_fb_easing( $_POST['easingOut'] );
		$fb['changeSpeed'] = sspdt_posint( $_POST['changeSpeed'] );
		
		$feed_options = array();
		
		$feed_options['feed_url'] = esc_url( $_POST['feed_url'] );
		$feed_options['secret'] = sspdt_nohtml( $_POST['secret'] );
		
		
		update_option( 'sspdt_format_options', $format_options );
		update_option( 'sspdt_defaults', $defaults );
		update_option( 'sspdt_fancybox', $fb );
		update_option( 'sspdt_feed_options', $feed_options);
		
	}
	
		if(get_option('sspdt_api_key') == null) {
			?>
			<div id="message" class="error">
				<p><strong><?php _e('You must define the API settings. Otherwise this plugin won\'t work.', 'sspdt'); ?></strong></p>
			</div><?php 
		}
		
		$fo = get_option('sspdt_feed_options');
		if($fo['feed_url'] == null || $fo['feed_url'] == '') {

		}
	
		if(get_option('sspdt_format_options') == null) {
		
			$format_options['grid_width'] = '60';
			$format_options['grid_height'] = '60';
			$format_options['grid_crop'] = '1';
			$format_options['grid_quality'] = '75';
			$format_options['grid_sharpen'] = '1';
			
			$format_options['thumb_width'] = '240';
			$format_options['thumb_height'] = '240';
			$format_options['thumb_crop'] = '0';
			$format_options['thumb_quality'] = '80';
			$format_options['thumb_sharpen'] = '1';
			$format_options['thumb_align'] = 'alignleft';
			$format_options['thumb_caption'] = '1';
			
			$format_options['large_width'] = '1000';
			$format_options['large_height'] = '720';
			$format_options['large_crop'] = '0';
			$format_options['large_quality'] = '85';
			$format_options['large_sharpen'] = '1';
			//$format_options['large_caption'] = '1';
			
			$format_options['preview_width'] = '180';
			$format_options['preview_height'] = '180';
			$format_options['preview_crop'] = '0';
			$format_options['preview_quality'] = '75';
			$format_options['preview_sharpen'] = '1';
			
			$format_options['full_width'] = '1920';
			$format_options['full_height'] = '1920';
			$format_options['full_crop'] = '0';
			$format_options['full_quality'] = '90';
			$format_options['full_sharpen'] = '1';
			
			update_option( 'sspdt_format_options', $format_options );
		}
		
		if(get_option('sspdt_defaults') == null) {
			$defaults['model'] = 'gallery';
			$defaults['model_id'] = '1';
			$defaults['limit'] = '12';
			$defaults['tags'] = '';
			$defaults['tagmode'] = 'one';
			$defaults['sort_on'] = 'captured_on';
			$defaults['sort_direction'] = 'DESC';
			$defaults['rss'] = '0';	
			
			update_option( 'sspdt_defaults', $defaults );
		}
		
		if(get_option('sspdt_fancybox') == null) {
			$fb['padding'] = '10';
			$fb['margin'] = '20';
			
			$fb['titleShow'] = '1';
			$fb['titlePosition'] = 'outside';
			$fb['counterShow'] = '0';
			
			$fb['overlayShow'] = '1';
			$fb['overlayOpacity'] = '0.3';
			$fb['overlayColor'] = '#666';
			
			$fb['cyclic'] = '0';
			$fb['showNavArrows'] = '1';
			$fb['showCloseButton'] = '1';
			$fb['enableEscapeButton'] = '1';
			
			$fb['transitionIn'] = '1';
			$fb['speedIn'] = '300';
			$fb['easingIn'] = 'linear';
			$fb['transitionOut'] = '1';
			$fb['speedOut'] = '400';
			$fb['easingOut'] = 'linear';
			$fb['changeSpeed'] = '400';	
			
			update_option( 'sspdt_fancybox', $fb );
		}
	
		$format_options = get_option('sspdt_format_options');
		$defaults = get_option('sspdt_defaults');
		$fb = get_option('sspdt_fancybox');
		$feed_options = get_option('sspdt_feed_options');
		
		$imdir =  WP_PLUGIN_URL . "/ssp-director-tools/images/";

?>

		<?php $phpversion = explode( ".", phpversion()); ?>

	<?php if ( $phpversion[0] < "5" ) { ?>
	<div class="error" id="message">
		<p><strong><?php _e('Your PHP version is too old.', 'sspdt');?></strong></p>
		<p><?php _e('The DirectorPHP API needs at least PHP 5. Please install it or get in touch with your internet provider or system administrator', 'sspdt'); ?></p>
	</div>
	<?php } 
	?>
	
	<?php if ( !function_exists ( curl_version ) ) { ?>
	<div class="error fade" id="message">
		<p><strong><?php _e('php_curl is not installed.', 'sspdt');?></strong></p>
		<p><?php _e('SSP Director Tools need php_curl be installed to work properly.', 'sspdt'); ?></p>
	</div>
	<?php } 
	?>
	
<script type="text/javascript">
<!--
function sort_on_change() {
	var sorton = (this.document.getElementsByName("sort_on")[0].value);
	this.document.getElementsByName("sort_direction")[0].disabled =  sorton == "null" || sorton == "random";
	
}

function tags_on_change() {
	this.document.getElementById("tagmode").disabled = this.document.getElementById("tags").value == '';
}

function model_on_change() {
	var mymodel = this.document.getElementById("model").value;
	this.document.getElementById("model_id").disabled = mymodel == "null";
}
//-->
</script>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php _e('SlideShowPro Director Tools', 'sspdt'); ?></h2>
<div class="narrow">
<form action="" method="post" id="sspdt-conf" >

<?php sspdt_nonce_field($sspdt_nonce); ?>

<h3><?php _e('API Settings', 'sspdt'); ?></h3>
	<p><i><?php _e('The settings can be found on the "System Info" page of your SlideShowPro Director installation.', 'sspdt'); ?></i></p>

	<table class="form-table">
		<tr valign="middle">
			<th scope="row">
				<label for="sspdt_api_key"><?php _e('API Key', 'sspdt'); ?></label>
			</th> 
			<td><input id="sspdt_api_key" name="sspdt_api_key" type="text" size="60" value="<?php echo get_option('sspdt_api_key'); ?>" /> 
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="sspdt_api_path"><?php _e('API Path', 'sspdt'); ?></label>
			</th> 
			<td><input id="sspdt_api_path" name="sspdt_api_path" type="text" size="60" value="<?php echo get_option('sspdt_api_path'); ?>" /> 
			</td>
		</tr>
	</table>

	<?php if (!is_writable ( realpath(dirname(__FILE__)) . '/api/cache' )) { 
		$disable_cache = 1;
		update_option('sspdt_api_cache', '0');
		echo "<div class='updated fade'><p><strong>";
		_e('Caching disabled', 'sspdt');
		echo "</strong></p><p>";
		printf( __( "Your cache directory %s is not writable.", "sspdt"), "<code>". realpath(dirname(__FILE__)) ."/api/cache</code>");
		echo "<br />";
		_e("You must allow your web server to write to it in order to enable caching.", "sspdt" );
		echo "</p></div>";
	}?>

	<table class="form-table">
		<tr valign="middle">
			<th scope="row">
				<label for="sspdt_api_cache"><?php _e('API Cache', 'sspdt'); ?></label>
			</th> 
			<td>
			<input style='margin-right:6px;' <?php if ($disable_cache == 1) { echo "DISABLED";} ?> id="sspdt_api_cache" name="sspdt_api_cache" type="checkbox" value="1" <?php if (get_option('sspdt_api_cache') == '1') {echo "checked = 'checked'";} ?> />
			<?php _e('API Cache activated', 'sspdt'); ?><i style='margin-left:20px;'><?php _e('The API cache can improve performance dramatically.', 'sspdt'); ?></i><br/>
			</td>
		</tr>
	</table>
	
	<h3><?php _e('Photo Feed Options', 'sspdt'); ?></h3>
	<p><i><?php _e('Needed, if you want to use the photo feed functionality.', 'sspdt'); ?></i></p>
	<table class="form-table">
		<tr valign="middle">
			<th scope="row"><label for="feed_url"><?php _e('Photo Feed URL', 'sspdt'); ?></label></th>
			<td><input id="feed_url" name="feed_url" type="text" size="60" value="<?php echo $feed_options['feed_url']; ?>" /></td>
		</tr>
		<tr valign="middle">
			<th scope="row"><label for="secret"><?php _e('Secret', 'sspdt'); ?></label></th>
			<td><input id="secret" name="secret" type="text" size="60" value="<?php echo $feed_options['secret']; ?>" /></td>
		</tr>
	</table>
	
	<h3><?php _e('Image Sizes an Handling', 'sspdt'); ?></h3>
	<p><i><?php _e('Define image sizes, quality and cropping.', 'sspdt'); ?></i></p>
	<table class="wp-list-table widefat" cellspacing="0">
		<thead>
			<tr valign="middle" >
				<th>&nbsp;</th>
				<th class="manage-column"><?php _e('Width', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Height', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Quality', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Cropping', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Sharpening', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Alignment', 'sspdt'); ?></th>
				<th class="manage-column"><?php _e('Caption', 'sspdt'); ?></th>
			</tr>
		</thead>
		<tbody>
		<tr valign="middle">
			<th scope="row"><img class="sspdt_handling_icon" src="<?php echo $imdir . 'grid.png'?>" width="20" height="20" alt="icon" />
				<label for="sspdt_grid" ><?php _e('Grid', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="grid_width" name="grid_width" type="text" size="4" value="<?php echo $format_options['grid_width']; ?>" />
			</td>
			<td>
				<input id="grid_height" name="grid_height" type="text" size="4" value="<?php echo $format_options['grid_height']; ?>" /> 
			</td>
			<td>
				<input id="grid_quality" name="grid_quality" type="text" size="4" value="<?php echo $format_options['grid_quality']; ?>" /> 
			</td>
			<td>
				<input id="grid_crop" name="grid_crop" type="checkbox" value="1" <?php if ( $format_options['grid_crop'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td>
				<input id="grid_sharpen" name="grid_sharpen" type="checkbox" value="1" <?php if ( $format_options['grid_sharpen'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr valign="middle">
			<th scope="row"><img class="sspdt_handling_icon"  src="<?php echo $imdir . 'thumb.png'?>" width="20" height="20" alt="icon" />
				<label for="sspdt_thumb"><?php _e('Thumbnail', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="thumb_width" name="thumb_width" type="text" size="4" value="<?php echo $format_options['thumb_width']; ?>" /> 
			</td>
			<td>
				<input id="thumb_height" name="thumb_height" type="text" size="4" value="<?php echo $format_options['thumb_height']; ?>" /> 
			</td>
			<td>
				<input id="thumb_quality" name="thumb_quality" type="text" size="4" value="<?php echo $format_options['thumb_quality']; ?>" /> 
			</td>
			<td>
				<input id="thumb_crop" name="thumb_crop" type="checkbox" value="1" <?php if ( $format_options['thumb_crop'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td>
				<input id="thumb_sharpen" name="thumb_sharpen" type="checkbox" value="1" <?php if ( $format_options['thumb_sharpen'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td>
				<select id="thumb_align" name="thumb_align">
					<option value="alignnone" <?php if($format_options['thumb_align'] == 'alignnone') {echo "selected";} ?> ><?php _e('none', 'sspdt'); ?></option>
					<option value="alignleft" <?php if($format_options['thumb_align'] == 'alignleft') {echo "selected";} ?> ><?php _e('left', 'sspdt'); ?></option>
					<option value="aligncenter" <?php if($format_options['thumb_align'] == 'aligncenter') {echo "selected";} ?> ><?php _e('center', 'sspdt'); ?></option>
					<option value="alignright" <?php if($format_options['thumb_align'] == 'alignright') {echo "selected";} ?> ><?php _e('right', 'sspdt'); ?></option>
				</select>
			</td>
			<td>
				<input id="thumb_caption" name="thumb_caption" type="checkbox" value="1" <?php if ( $format_options['thumb_caption'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row"><img class="sspdt_handling_icon"  src="<?php echo $imdir . 'large.png'?>" width="20" height="20" alt="icon" />
				<label for="sspdt_large"><?php _e('Image', 'sspdt'); ?></label>
			</th> 
			<td>
				<input id="large_width" name="large_width" type="text" size="4" value="<?php echo $format_options['large_width']; ?>" /> 
			</td>
			<td>
				<input id="large_height" name="large_height" type="text" size="4" value="<?php echo $format_options['large_height']; ?>" /> 
			</td>
			<td>
				<input id="large_quality" name="large_quality" type="text" size="4" value="<?php echo $format_options['large_quality']; ?>" /> 
			</td>
			<td>
				<input id="large_crop" name="large_crop" type="checkbox" value="1" <?php if ( $format_options['large_crop'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td>
				<input id="large_sharpen" name="large_sharpen" type="checkbox" value="1" <?php if ( $format_options['large_sharpen'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
			<td></td>
			<td>
				<input id="titleShow" name="titleShow" type="checkbox" value="1" <?php if ( $fb['titleShow'] == '1') { echo "checked = 'checked'";}  ?>/>
			</td>
		</tr>

		</tbody>
		<tfoot>
			<tr valign="middle" >
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</tfoot>
	</table>
	
	<h3><?php _e('Photo Grid Defaults', 'sspdt'); ?></h3>
	<p><i><?php _e('Default settings for SSP Director Photo Grids.', 'sspdt'); ?><br/>
	<?php _e('These settings can be overridden by the photo grid shortcode [sspd] attributes.', 'sspdt'); ?></i></p>
	<table class="form-table">
		<tr valign="middle">
			<th scope="row">
				<label for="model"><?php _e('Scope', 'sspdt'); ?></label>
			</th>
			<td>
				<select id="model" name="model" style="min-width:120px;" onchange="model_on_change();">
					<option value="null" <?php if($defaults['model'] == 'null') {echo "selected";} ?> ><?php _e('-- Not specified --', 'sspdt')?></option>
					<option value="gallery" <?php if($defaults['model'] == 'gallery') {echo "selected";} ?> ><?php _e('Gallery', 'sspdt'); ?></option>
					<option value="album" <?php if($defaults['model'] == 'album') {echo "selected";} ?> ><?php _e('Album', 'sspdt'); ?></option>
				</select> 
				ID <input id="model_id" name="model_id" size="4" type="text" value="<?php echo $defaults['model_id']; ?>" <?php if($defaults['model'] == null || $defaults['model'] == 'null') {echo "disabled";} ?>/>
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row">
				<label for="limit"><?php _e('Limit', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="limit" name="limit" type="text" size="4" value="<?php echo $defaults['limit']; ?>" />
				<i><?php _e('Leave blank if no limit should be specified.', 'sspdt');?></i>
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row">
				<label for="tags"><?php _e('Tags', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="tags" name="tags" type="text" size="40" value="<?php echo $defaults['tags']; ?>" onkeypress="tags_on_change();" onblur="tags_on_change();" />
				<select id="tagmode" name="tagmode" <?php if($defaults['tags'] == null || $defaults['tags'] == '') {echo "disabled";} ?> >
					<option value="all" <?php if($defaults['tagmode'] == 'all') {echo "selected";} ?> ><?php _e('all', 'sspdt'); ?></option>
					<option value="one" <?php if($defaults['tagmode'] == 'one') {echo "selected";} ?> ><?php _e('one', 'sspdt'); ?></option>
				</select>
				<i><?php _e('Leave blank if no tags should be specified.', 'sspdt'); ?></i>
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row">
				<label for="sort_on"><?php _e('Sort', 'sspdt'); ?></label>
			</th>
			<td>
				<select id="sort_on" name="sort_on" style="min-width:120px;" onchange="sort_on_change();">
					<option value="null" <?php if($defaults['sort_on'] == 'null') {echo "selected";} ?> ><?php _e('-- Not specified --', 'sspdt'); ?></option>
					<option value="created_on" <?php if($defaults['sort_on'] == 'created_on') {echo "selected";} ?> ><?php _e('Creation Date', 'sspdt'); ?></option>
					<option value="captured_on" <?php if($defaults['sort_on'] == 'captured_on') {echo "selected";} ?> ><?php _e('Capture Date', 'sspdt'); ?></option>
					<option value="modified_on" <?php if($defaults['sort_on'] == 'modified_on') {echo "selected";} ?> ><?php _e('Modification Date', 'sspdt'); ?></option>
					<option value="filename" <?php if($defaults['sort_on'] == 'filename') {echo "selected";} ?> ><?php _e('File Name', 'sspdt'); ?></option>
					<option value="random" <?php if($defaults['sort_on'] == 'random') {echo "selected";} ?> ><?php _e('Random', 'sspdt'); ?></option>
				</select>
				<select id="sort_direction" name="sort_direction" style="min-width:80px;" <?php if($defaults['sort_on'] == 'null' || $defaults['sort_on'] == null || $defaults['sort_on'] == 'random') {echo "disabled";} ?> >
					<option value="ASC" <?php if($defaults['sort_direction'] == 'ASC') {echo "selected";} ?> ><?php _e('ascending', 'sspdt'); ?></option>
					<option value="DESC" <?php if($defaults['sort_direction'] == 'DESC') {echo "selected";} ?> ><?php _e('descending', 'sspdt'); ?></option>
				</select>
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row">
				<label for="rss"><?php _e('Photo Feed', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="rss" name="rss" type="checkbox" value="1" <?php if($defaults['rss'] == "1")  { echo "checked = 'checked'";} ?> /> <?php _e('Show a link to a photo feed for gallery images.', 'sspdt'); ?>
			</td>
		</tr>
	</table>
	
	<h3><?php _e('Presentation', 'sspdt'); ?></h3>
	<p><i><?php _e('FancyBox settings for image presentation.', 'sspdt'); ?></i></p>
	<table class="form-table">
		<tr valign="middle">
			<th scope="row">
				<label><?php _e('Box', 'sspdt'); ?></label>
			</th>
			<td>
				<?php _e('Padding', 'sspdt');?>
				<input id="padding" name="padding" type="text" size="2" value="<?php echo $fb['padding']; ?>"/>px
				<span style="margin-left: 20px;"><?php _e('Margin', 'sspdt'); ?></span>
				<input id="margin" name="margin" type="text" size="2" value="<?php echo $fb['margin']; ?>"/>px
			</td>
		</tr>
		<tr valign="middle">
			<th scope="row">
				<label for="fb_title"><?php _e('Caption', 'sspdt'); ?></label>
			</th>
			<td>
				<?php _e('Position:', 'sspdt'); ?>
				<select id="titlePosition" name="titlePosition">
					<option value="outside" <?php if($fb['titlePosition'] == 'outside') {echo "selected";} ?> ><?php _e('outside frame', 'sspdt'); ?></option>
					<option value="inside" <?php if($fb['titlePosition'] == 'inside') {echo "selected";} ?> ><?php _e('inside frame', 'sspdt'); ?></option>
					<option value="over" <?php if($fb['titlePosition'] == 'over') {echo "selected";} ?> ><?php _e('over image', 'sspdt'); ?></option>
				</select>
			</td>
		</tr>
		<tr valign="middle">
			<th>
				<label for="fb_overlay"><?php _e('Overlay', 'sspdt'); ?></label>
			</th>
			<td>
				<input id="overlayShow" name="overlayShow" type="checkbox" value="1" <?php if($fb['overlayShow'] == "1")  { echo "checked = 'checked'";} ?> />
				<?php _e('Show overlay', 'sspdt'); ?> 
				<span style="margin-left: 20px;"><?php _e('Opacity:', 'sspdt'); ?> </span>
				<select id="overlayOpacity" name="overlayOpacity">
					<option value="0" <?php if($fb['overlayOpacity'] == '0') {echo "selected";} ?> >0</option>
					<option value="0.1" <?php if($fb['overlayOpacity'] == '0.1') {echo "selected";} ?> >0.1</option>
					<option value="0.2" <?php if($fb['overlayOpacity'] == '0.2') {echo "selected";} ?> >0.2</option>
					<option value="0.3" <?php if($fb['overlayOpacity'] == '0.3') {echo "selected";} ?> >0.3</option>
					<option value="0.4" <?php if($fb['overlayOpacity'] == '0.4') {echo "selected";} ?> >0.4</option>
					<option value="0.5" <?php if($fb['overlayOpacity'] == '0.5') {echo "selected";} ?> >0.5</option>
					<option value="0.6" <?php if($fb['overlayOpacity'] == '0.6') {echo "selected";} ?> >0.6</option>
					<option value="0.7" <?php if($fb['overlayOpacity'] == '0.7') {echo "selected";} ?> >0.7</option>
					<option value="0.8" <?php if($fb['overlayOpacity'] == '0.8') {echo "selected";} ?> >0.8</option>
					<option value="0.9" <?php if($fb['overlayOpacity'] == '0.9') {echo "selected";} ?> >0.9</option>
					<option value="1" <?php if($fb['overlayOpacity'] == '1') {echo "selected";} ?> >1</option>
				</select>
				<span style="margin-left: 20px;"><?php _e('Color:', 'sspdt'); ?></span>
				<input type="text" id="overlayColor" name="overlayColor" value="<?php echo $fb['overlayColor'];?>" size="8"/>
			</td>
		</tr>
		<tr valign="middle">
			<th>
				<label><?php _e('Navigation', 'sspdt'); ?></label>
			</th>
			<td>
				<p><input type="checkbox" id="cyclic" name="cyclic" value="1" <?php if($fb['cyclic'] == "1")  { echo "checked = 'checked'";} ?> /> <?php _e('Cyclic navigation', 'sspdt'); ?></p>
				<p><input type ="checkbox" id="showCloseButton" name="showCloseButton" value="1" <?php if($fb['showCloseButton'] == "1")  { echo "checked = 'checked'";} ?>/> <?php _e('Show close button', 'sspdt'); ?></p>
				<p><input type="checkbox" id="showNavArrows" name="showNavArrows" value="1" <?php if($fb['showNavArrows'] == "1")  { echo "checked = 'checked'";} ?> /> <?php _e('Show navigation arrows', 'sspdt'); ?></p>
				<p><input type="checkbox" id="enableEscapeButton" name="enableEscapeButton" value="1" <?php if($fb['enableEscapeButton'] == "1")  { echo "checked = 'checked'";} ?> /> <?php _e('Enable ESC key', 'sspdt'); ?></p>
			</td>
		</tr>
		<tr valign="middle">
			<th>
				<label><?php _e('Transitions', 'sspdt'); ?></label>
			</th>
			<td>
					<p>
					<?php _e('In:', 'sspdt'); ?>
					<select id="transitionIn" name="transitionIn">
						<option value="none" <?php if($fb['transitionIn'] == 'none') {echo "selected";} ?>><?php _e('none', 'sspdt'); ?></option>
						<option value="fade" <?php if($fb['transitionIn'] == 'fade') {echo "selected";} ?>><?php _e('fade', 'sspdt'); ?></option>
						<option value="elastic" <?php if($fb['transitionIn'] == 'elastic') {echo "selected";} ?>><?php _e('elastic', 'sspdt'); ?></option>
					</select>
					<span style="margin-left: 20px;"><?php _e('Speed:', 'sspdt'); ?> </span>
					<input id="speedIn" name="speedIn" type="text" size="4" value="<?php echo $fb['speedIn']; ?>" />ms
					<span style="margin-left: 20px;"><?php _e('Easing:', 'sspdt'); ?> </span>
					<select id="easingIn" name="easingIn">
						<option value="linear" <?php if($fb['easingIn'] == 'linear') {echo "selected";} ?>><?php _e('Linear', 'sspdt'); ?></option>
						<option value="swing" <?php if($fb['easingIn'] == 'swing') {echo "selected";} ?>><?php _e('Swing', 'sspdt'); ?></option>
					</select>
					</p><p>
					<?php _e('Out:', 'sspdt'); ?>
					<select id="transitionOut" name="transitionOut">
						<option value="none" <?php if($fb['transitionOut'] == 'none') {echo "selected";} ?>><?php _e('none', 'sspdt'); ?></option>
						<option value="fade" <?php if($fb['transitionOut'] == 'fade') {echo "selected";} ?>><?php _e('fade', 'sspdt'); ?></option>
						<option value="elastic" <?php if($fb['transitionOut'] == 'elastic') {echo "selected";} ?>><?php _e('elastic', 'sspdt'); ?></option>
					</select>
					<span style="margin-left: 20px;"><?php _e('Speed:', 'sspdt'); ?> </span>
					<input id="speedOut" name="speedOut" type="text" size="4" value="<?php echo $fb['speedOut']; ?>" />ms
					<span style="margin-left: 20px;"><?php _e('Easing:', 'sspdt'); ?> </span>
					<select id="easingOut" name="easingOut">
						<option value="linear" <?php if($fb['easingOut'] == 'linear') {echo "selected";} ?>><?php _e('Linear', 'sspdt'); ?></option>
						<option value="swing" <?php if($fb['easingOut'] == 'swing') {echo "selected";} ?>><?php _e('Swing', 'sspdt'); ?></option>
					</select>
					</p><p>
					<?php _e('Change speed:', 'sspdt'); ?>
					<input id="changeSpeed" name="changeSpeed" type="text" size="4" value="<?php echo $fb['changeSpeed']; ?>" />ms <i style="margin-left: 20px;"><?php _e('Speed of resizing when changing gallery items, in milliseconds', 'sspdt'); ?></i>
					</p>
			</td>
		</tr>
		
	</table>


	<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php _e('Update settings', 'sspdt'); ?>" /></p>
</form>

</div>

	

</div>
<?php
} ?>