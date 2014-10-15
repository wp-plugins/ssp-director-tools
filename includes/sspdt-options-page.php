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
				
			?>
<div id="message" class="updated fade">
	<p>
		<strong><?php _e('Settings have been saved.', 'sspdt'); ?>
		</strong>
	</p>
</div>
			<?php
			unset( $director );
			unset( $_POST['sspdt_api_key']);
			unset( $_POST['sspdt_api_key']);
				
		}

		else {

			?>
<div id="message" class="error">
	<p>
		<strong><?php _e('API Settings seem wrong. Please double check them.', 'sspdt'); ?>
		</strong>
	</p>
</div>
			<?php
		}

		update_option( 'sspdt_api_cache', $_POST['sspdt_api_cache'] );

		$format_options                           = array();

		$format_options['grid_width']             = sspdt_posint ( $_POST['grid_width'] );
		$format_options['grid_height']            = sspdt_posint ( $_POST['grid_height'] );
		$format_options['grid_crop']              = sspdt_bool( $_POST['grid_crop'] );
		$format_options['grid_quality']           = sspdt_posint ( $_POST['grid_quality'] );
		$format_options['grid_sharpen']           = sspdt_bool( $_POST['grid_sharpen'] );

		$format_options['thumb_width']            = sspdt_posint( $_POST['thumb_width'] );
		$format_options['thumb_height']           = sspdt_posint( $_POST['thumb_height'] );
		$format_options['thumb_crop']             = sspdt_bool( $_POST['thumb_crop'] );
		$format_options['thumb_quality']          = sspdt_posint( $_POST['thumb_quality'] );
		$format_options['thumb_sharpen']          = sspdt_bool( $_POST['thumb_sharpen'] );
		$format_options['thumb_align']            = sspdt_align( $_POST['thumb_align'] );
		$format_options['thumb_caption']          = sspdt_bool( $_POST['thumb_caption'] );
		$format_options['thumb_caption_format']   = htmlspecialchars( sspdt_html_format( $_POST['thumb_caption_format'] ), ENT_COMPAT, "UTF-8", false );
		$format_options['thumb_watermark']        = sspdt_bool( $_POST['thumb_watermark'] );

		$format_options['large_width']            = sspdt_posint( $_POST['large_width'] );
		$format_options['large_height']           = sspdt_posint( $_POST['large_height'] );
		$format_options['large_crop']             = sspdt_bool( $_POST['large_crop'] );
		$format_options['large_quality']          = sspdt_posint( $_POST['large_quality'] );
		$format_options['large_sharpen']          = sspdt_bool( $_POST['large_sharpen'] );
		$format_options['large_caption_format']   = htmlspecialchars( sspdt_html_format( $_POST['large_caption_format'] ), ENT_COMPAT, "UTF-8", false );
		$format_options['large_watermark']        = sspdt_bool( $_POST['large_watermark'] );
		
		$format_options['date_format']            = sspdt_nohtml( $_POST['date_format'] );
		
		//var_dump($format_options);

		$defaults = array();

		$defaults['model']            = sspdt_model( $_POST['model'] );
		$defaults['model_id']         = sspdt_posint( $_POST['model_id'] );
		$defaults['limit']            = sspdt_posint( $_POST['limit'] );
		$defaults['tags']             = sspdt_nohtml( $_POST['tags'] );
		$defaults['tagmode']          = sspdt_tagmode( $_POST['tagmode'] );
		$defaults['sort_on']          = sspdt_sort_on( $_POST['sort_on'] );
		$defaults['sort_direction']   = sspdt_sort_direction( $_POST['sort_direction'] );
		$defaults['rss']              = sspdt_bool( $_POST['rss'] );

		$fb = array();

		$fb['padding']            = sspdt_posint( $_POST['padding'] );
		$fb['margin']             = sspdt_posint( $_POST['margin'] );

		$fb['titleShow']          = sspdt_bool( $_POST['titleShow'] );
		$fb['titlePosition']      = sspdt_fb_title_position( $_POST['titlePosition'] );
		$fb['counterShow']        = sspdt_bool( $_POST['counterShow'] );

		$fb['overlayShow']        = sspdt_bool( $_POST['overlayShow'] );
		$fb['overlayOpacity']     = sspdt_fb_opacity( $_POST['overlayOpacity'] );
		$fb['overlayColor']       = sspdt_color( $_POST['overlayColor'] );

		$fb['cyclic']             = sspdt_bool( $_POST['cyclic'] );
		$fb['showNavArrows']      = sspdt_bool( $_POST['showNavArrows'] );
		$fb['showCloseButton']    = sspdt_bool( $_POST['showCloseButton'] );
		$fb['enableEscapeButton'] = sspdt_bool( $_POST['enableEscapeButton'] );

		$fb['transitionIn']       = sspdt_fb_transition( $_POST['transitionIn'] );
		$fb['speedIn']            = sspdt_posint( $_POST['speedIn'] );
		$fb['easingIn']           = sspdt_fb_easing( $_POST['easingIn'] );
		$fb['transitionOut']      = sspdt_fb_transition( $_POST['transitionOut'] );
		$fb['speedOut']           = sspdt_posint( $_POST['speedOut'] );
		$fb['easingOut']          = sspdt_fb_easing( $_POST['easingOut'] );
		$fb['changeSpeed']        = sspdt_posint( $_POST['changeSpeed'] );

		$feed_options = array();

		$feed_options['feed_url'] = rtrim( esc_url( $_POST['feed_url'] ), " /") . "/";
		$feed_options['secret']   = sspdt_nohtml( $_POST['secret'] );


		update_option( 'sspdt_format_options', $format_options );
		update_option( 'sspdt_defaults', $defaults );
		update_option( 'sspdt_fancybox', $fb );
		update_option( 'sspdt_feed_options', $feed_options);

	}

	if(get_option('sspdt_api_key') == null) {
		?>
<div id="message" class="error">
	<p>
		<strong><?php _e('You must define the API settings. Otherwise this plugin won\'t work.', 'sspdt'); ?>
		</strong>
	</p>
</div>
		<?php	
	}


	$format_options    = get_option('sspdt_format_options');
	$defaults          = get_option('sspdt_defaults');
	$fb                = get_option('sspdt_fancybox');
	$feed_options      = get_option('sspdt_feed_options');

	$imdir             = WP_PLUGIN_URL . "/ssp-director-tools/images/";

	?>

	<?php $phpversion = explode( ".", phpversion()); ?>

	<?php if ( $phpversion[0] < "5" ) { ?>
<div class="error" id="message">
	<p>
		<strong><?php _e('Your PHP version is too old.', 'sspdt');?> </strong>
	</p>
	<p>
	<?php _e('The DirectorPHP API needs at least PHP 5. Please install it or get in touch with your internet provider or system administrator', 'sspdt'); ?>
	</p>
</div>
	<?php }
	?>

	<?php if ( !function_exists ( curl_version ) ) { ?>
<div class="error fade" id="message">
	<p>
		<strong><?php _e('php_curl is not installed.', 'sspdt');?> </strong>
	</p>
	<p>
	<?php _e('SSP Director Tools need php_curl be installed to work properly.', 'sspdt'); ?>
	</p>
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

var $j = jQuery.noConflict()

function showhide(what) {
	var $j = jQuery.noConflict()
    if (document.getElementById(what).style.display == 'none') {
   $j('#' + what).slideDown(600);
    } else {
   $j('#' + what).hide(200);
    }
}


$j(document).ready(function() {
	  if (window.location.hash) {
	    var h = String(window.location.hash.substring(1));
	    if ($j(h)) {
	      showhide(h);
	    }
	  }
	});

//-->
</script>

<div class="wrap">
<?php screen_icon(); ?>
	<h2>
	<?php _e('SlideShowPro Director Tools', 'sspdt'); ?>
	</h2>
	<div class="narrow">
		<form action="" method="post" id="sspdt-conf">

		<?php sspdt_nonce_field($sspdt_nonce); ?>

			<h3>
			<?php _e('API Settings', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('The settings can be found on the "System Info" page of your SlideShowPro Director installation.', 'sspdt'); ?>
				</i>
			</p>

			<table class="form-table">
				<tr valign="middle">
					<th scope="row"><label for="sspdt_api_key"><?php _e('API Key', 'sspdt'); ?>
					</label>
					</th>
					<td><input id="sspdt_api_key" name="sspdt_api_key" type="text"
						size="60" value="<?php echo get_option('sspdt_api_key'); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sspdt_api_path"><?php _e('API Path', 'sspdt'); ?>
					</label>
					</th>
					<td><input id="sspdt_api_path" name="sspdt_api_path" type="text"
						size="60" value="<?php echo get_option('sspdt_api_path'); ?>" />
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
					<th scope="row"><label for="sspdt_api_cache"><?php _e('API Cache', 'sspdt'); ?>
					</label>
					</th>
					<td><input style='margin-right: 6px;'
			<?php if ($disable_cache == 1) { echo "DISABLED";} ?>
						id="sspdt_api_cache" name="sspdt_api_cache" type="checkbox"
						value="1"
						<?php if (get_option('sspdt_api_cache') == '1') {echo "checked = 'checked'";} ?> />
						<?php _e('API Cache activated', 'sspdt'); ?><i
						style='margin-left: 20px;'><?php _e('The API cache can improve performance dramatically.', 'sspdt'); ?>
					</i><br />
					</td>
				</tr>
			</table>

			<h3>
			<?php _e('Photo Feed Options', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('Needed, if you want to use the photo feed functionality.', 'sspdt'); ?>
				</i>
			</p>
			<table class="form-table">
				<tr valign="middle">
					<th scope="row"><label for="feed_url"><?php _e('Photo Feed URL', 'sspdt'); ?>
					</label></th>
					<td><input id="feed_url" name="feed_url" type="text" size="60"
						value="<?php echo $feed_options['feed_url']; ?>" /><br/><i> <?php _e('Path to the photo feed installation with tailing slash.', 'sspdt'); ?></i></td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="secret"><?php _e('Secret', 'sspdt'); ?>
					</label></th>
					<td><input id="secret" name="secret" type="text" size="60"
						value="<?php echo $feed_options['secret']; ?>" /><br/><i> <?php _e('Enter the same secret as in the config.php file of your photo feed installation.', 'sspdt'); ?></i></td>
				</tr>
			</table>

			<h3>
			<?php _e('Image Sizes an Handling', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('Define image sizes, quality and cropping.', 'sspdt'); ?>
				</i>
			</p>
			<table class="wp-list-table widefat" cellspacing="0">
				<thead>
					<tr valign="middle">
						<th>&nbsp;</th>
						<th class="manage-column"><?php _e('Width', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Height', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Quality', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Cropping', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Sharpening', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Alignment', 'sspdt'); ?></th>
						<th class="manage-column"><?php _e('Watermark', 'sspdt'); ?> <sup>*</sup></th>
					</tr>
				</thead>
				<tbody>
					<tr valign="middle">
						<th scope="row"><img class="sspdt_handling_icon"
							src="<?php echo $imdir . 'grid.png'?>" width="20" height="20"
							alt="icon" /> <label for="sspdt_grid"><?php _e('Grid', 'sspdt'); ?>
						</label>
						</th>
						<td><input id="grid_width" name="grid_width" type="text" size="4"
							value="<?php echo $format_options['grid_width']; ?>" />
						</td>
						<td><input id="grid_height" name="grid_height" type="text"
							size="4" value="<?php echo $format_options['grid_height']; ?>" />
						</td>
						<td><input id="grid_quality" name="grid_quality" type="text"
							size="4" value="<?php echo $format_options['grid_quality']; ?>" />
						</td>
						<td><input id="grid_crop" name="grid_crop" type="checkbox"
							value="1"
							<?php if ( $format_options['grid_crop'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td><input id="grid_sharpen" name="grid_sharpen" type="checkbox"
							value="1"
							<?php if ( $format_options['grid_sharpen'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr valign="middle">
						<th scope="row"><img class="sspdt_handling_icon"
							src="<?php echo $imdir . 'thumb.png'?>" width="20" height="20"
							alt="icon" /> <label for="sspdt_thumb"><?php _e('Thumbnail', 'sspdt'); ?>
						</label>
						</th>
						<td><input id="thumb_width" name="thumb_width" type="text"
							size="4" value="<?php echo $format_options['thumb_width']; ?>" />
						</td>
						<td><input id="thumb_height" name="thumb_height" type="text"
							size="4" value="<?php echo $format_options['thumb_height']; ?>" />
						</td>
						<td><input id="thumb_quality" name="thumb_quality" type="text"
							size="4" value="<?php echo $format_options['thumb_quality']; ?>" />
						</td>
						<td><input id="thumb_crop" name="thumb_crop" type="checkbox"
							value="1"
							<?php if ( $format_options['thumb_crop'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td><input id="thumb_sharpen" name="thumb_sharpen" type="checkbox"
							value="1"
							<?php if ( $format_options['thumb_sharpen'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td><select id="thumb_align" name="thumb_align">
								<option value="alignnone"
								<?php if($format_options['thumb_align'] == 'alignnone') {echo "selected";} ?>>
									<?php _e('none', 'sspdt'); ?>
								</option>
								<option value="alignleft"
								<?php if($format_options['thumb_align'] == 'alignleft') {echo "selected";} ?>>
									<?php _e('left', 'sspdt'); ?>
								</option>
								<option value="aligncenter"
								<?php if($format_options['thumb_align'] == 'aligncenter') {echo "selected";} ?>>
									<?php _e('center', 'sspdt'); ?>
								</option>
								<option value="alignright"
								<?php if($format_options['thumb_align'] == 'alignright') {echo "selected";} ?>>
									<?php _e('right', 'sspdt'); ?>
								</option>
						</select>
						</td>
						<td><input id="thumb_watermark" name="thumb_watermark" type="checkbox"
							value="1"
							<?php if ( $format_options['thumb_watermark'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
					</tr>
					<tr valign="middle">
						<th scope="row"><img class="sspdt_handling_icon"
							src="<?php echo $imdir . 'large.png'?>" width="20" height="20"
							alt="icon" /> <label for="sspdt_large"><?php _e('Image', 'sspdt'); ?>
						</label>
						</th>
						<td><input id="large_width" name="large_width" type="text"
							size="4" value="<?php echo $format_options['large_width']; ?>" />
						</td>
						<td><input id="large_height" name="large_height" type="text"
							size="4" value="<?php echo $format_options['large_height']; ?>" />
						</td>
						<td><input id="large_quality" name="large_quality" type="text"
							size="4" value="<?php echo $format_options['large_quality']; ?>" />
						</td>
						<td><input id="large_crop" name="large_crop" type="checkbox"
							value="1"
							<?php if ( $format_options['large_crop'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td><input id="large_sharpen" name="large_sharpen" type="checkbox"
							value="1"
							<?php if ( $format_options['large_sharpen'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
						<td></td>
						<td><input id="large_watermark" name="large_watermark" type="checkbox"
							value="1"
							<?php if ( $format_options['large_watermark'] == '1') { echo "checked = 'checked'";}  ?> />
						</td>
					</tr>

				</tbody>
				<tfoot>
					<tr valign="middle">
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><sup>*</sup><?php _e('if available', 'sspdt'); ?></th>
					</tr>
				</tfoot>
			</table>
			
			<h3>
			<?php _e('Captions', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('Define display and formatting of captions based on content metadata.', 'sspdt'); ?>
				</i>
			</p>
			<table class="form-table">
				<tr valign="middle">
					<th scope="row"><label for="preview_caption"><?php _e('Preview Captions', 'sspdt'); ?></label></th>
					<td>
						<input id="thumb_caption" name="thumb_caption" type="checkbox" style="margin-right:20px;"
							value="1"
							<?php if ( $format_options['thumb_caption'] == '1') { echo "checked = 'checked'";}  ?> /> Format  (<a href="javascript:showhide('caption_formatting_help');"><?php _e('Help', 'sspdt'); ?></a>)
						<input id="thumb_caption_format" name="thumb_caption_format" type="text" size="80" value="<?php echo $format_options['thumb_caption_format'];?>"></input>
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="large_caption"><?php _e('Presentation Captions', 'sspdt'); ?></label></th>
					<td><p>
						<input id="titleShow" name="titleShow" type="checkbox" style="margin-right:20px;"
							value="1"
							<?php if ( $fb['titleShow'] == '1') { echo "checked = 'checked'";}  ?> /> Format (<a href="javascript:showhide('caption_formatting_help');"><?php _e('Help', 'sspdt'); ?></a>)
						<input id="large_caption_format" name="large_caption_format" type="text" size="80" value="<?php echo $format_options['large_caption_format'];?>"></input>
						</p>
						<p>
							<?php _e('Position:', 'sspdt'); ?> <select id="titlePosition"
						name="titlePosition">
							<option value="outside"
							<?php if($fb['titlePosition'] == 'outside') {echo "selected";} ?>>
								<?php _e('outside frame', 'sspdt'); ?>
							</option>
							<option value="inside"
							<?php if($fb['titlePosition'] == 'inside') {echo "selected";} ?>>
								<?php _e('inside frame', 'sspdt'); ?>
							</option>
							<option value="over"
							<?php if($fb['titlePosition'] == 'over') {echo "selected";} ?>>
								<?php _e('over image', 'sspdt'); ?>
							</option>
					</select>
						</p>
					</td>
				</tr>
				<tr valign="middle" id="caption_formatting_help" style="display: none;">
					<th scope="row"></th>
					<td class="sspdt_info">
							<h4><?php _e('Placeholders', 'sspdt'); ?></h4>
							<p><?php _e('The following placeholders for SSP Director metadata are allowed in captions:', 'sspdt'); ?></p>
							<ul>
								<li><strong>%caption% </strong> <?php _e('The image caption. If not set, the IPTC caption is used', 'sspdt'); ?></li>
								<li><strong>%byline% </strong> <?php _e('The IPTC byline', 'sspdt'); ?></li>
								<li><strong>%date% </strong> <?php _e('The image capture date from the EXIF record', 'sspdt'); ?></li>
								<li><strong>%city% </strong> <?php _e('The IPTC city', 'sspdt'); ?></li>
								<li><strong>%country% </strong> <?php _e('The IPTC country', 'sspdt'); ?></li>
							</ul>
							<h4><?php _e('HTML Tags', 'sspdt'); ?></h4>
							<p><?php _e('The following HTML tags are allowed in captions:', 'sspdt'); ?></p>
							<p><pre>&lt;div style=""&gt;, &lt;p style=""&gt;, &lt;b&gt;, &lt;i&gt; &lt;strong&gt;, &lt;em&gt;, &lt;br&gt;</pre></p>
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="date_format"><?php _e('Date Format', 'sspdt'); ?></label></th>
					<td>
						<input id="date_format" name="date_format" type="text" size="8" value="<?php echo $format_options['date_format'];?>"></input><br/>
						<i><?php printf( __('Use PHP date formats. <a href="%s" target="_blank">Help</a>'), 'http://www.php.net/manual/en/function.date.php' ); ?></i>
					</td>
				</tr>
			</table>

			<h3>
			<?php _e('Photo Grid Defaults', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('Default settings for SSP Director Photo Grids.', 'sspdt'); ?><br />
				<?php _e('These settings can be overridden by the photo grid shortcode [sspd] attributes.', 'sspdt'); ?>
				</i>
			</p>
			<table class="form-table">
				<tr valign="middle">
					<th scope="row"><label for="model"><?php _e('Scope', 'sspdt'); ?> </label>
					</th>
					<td><select id="model" name="model" style="min-width: 120px;"
						onchange="model_on_change();">
							<option value="null"
							<?php if($defaults['model'] == 'null') {echo "selected";} ?>>
								<?php _e('-- Not specified --', 'sspdt')?>
							</option>
							<option value="gallery"
							<?php if($defaults['model'] == 'gallery') {echo "selected";} ?>>
								<?php _e('Gallery', 'sspdt'); ?>
							</option>
							<option value="album"
							<?php if($defaults['model'] == 'album') {echo "selected";} ?>>
								<?php _e('Album', 'sspdt'); ?>
							</option>
					</select> ID <input id="model_id" name="model_id" size="4"
						type="text" value="<?php echo $defaults['model_id']; ?>"
						<?php if($defaults['model'] == null || $defaults['model'] == 'null') {echo "disabled";} ?> />
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="limit"><?php _e('Limit', 'sspdt'); ?> </label>
					</th>
					<td><input id="limit" name="limit" type="text" size="4"
						value="<?php echo $defaults['limit']; ?>" /><br/><i><?php _e('Leave blank if no limit should be specified.', 'sspdt');?>
					</i>
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="tags"><?php _e('Tags', 'sspdt'); ?> </label>
					</th>
					<td><input id="tags" name="tags" type="text" size="40"
						value="<?php echo $defaults['tags']; ?>"
						onkeypress="tags_on_change();" onblur="tags_on_change();" /> <select
						id="tagmode" name="tagmode"
						<?php if($defaults['tags'] == null || $defaults['tags'] == '') {echo "disabled";} ?>>
							<option value="all"
							<?php if($defaults['tagmode'] == 'all') {echo "selected";} ?>>
								<?php _e('all', 'sspdt'); ?>
							</option>
							<option value="one"
							<?php if($defaults['tagmode'] == 'one') {echo "selected";} ?>>
								<?php _e('one', 'sspdt'); ?>
							</option>
					</select><br/><i><?php _e('Leave blank if no tags should be specified.', 'sspdt'); ?>
					</i>
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="sort_on"><?php _e('Sort', 'sspdt'); ?>
					</label>
					</th>
					<td><select id="sort_on" name="sort_on" style="min-width: 120px;"
						onchange="sort_on_change();">
							<option value="null"
							<?php if($defaults['sort_on'] == 'null') {echo "selected";} ?>>
								<?php _e('-- Not specified --', 'sspdt'); ?>
							</option>
							<option value="created_on"
							<?php if($defaults['sort_on'] == 'created_on') {echo "selected";} ?>>
								<?php _e('Creation Date', 'sspdt'); ?>
							</option>
							<option value="captured_on"
							<?php if($defaults['sort_on'] == 'captured_on') {echo "selected";} ?>>
								<?php _e('Capture Date', 'sspdt'); ?>
							</option>
							<option value="modified_on"
							<?php if($defaults['sort_on'] == 'modified_on') {echo "selected";} ?>>
								<?php _e('Modification Date', 'sspdt'); ?>
							</option>
							<option value="filename"
							<?php if($defaults['sort_on'] == 'filename') {echo "selected";} ?>>
								<?php _e('File Name', 'sspdt'); ?>
							</option>
							<option value="random"
							<?php if($defaults['sort_on'] == 'random') {echo "selected";} ?>>
								<?php _e('Random', 'sspdt'); ?>
							</option>
					</select> <select id="sort_direction" name="sort_direction"
						style="min-width: 80px;"
								<?php if($defaults['sort_on'] == 'null' || $defaults['sort_on'] == null || $defaults['sort_on'] == 'random') {echo "disabled";} ?>>
							<option value="ASC"
							<?php if($defaults['sort_direction'] == 'ASC') {echo "selected";} ?>>
								<?php _e('ascending', 'sspdt'); ?>
							</option>
							<option value="DESC"
							<?php if($defaults['sort_direction'] == 'DESC') {echo "selected";} ?>>
								<?php _e('descending', 'sspdt'); ?>
							</option>
					</select>
					</td>
				</tr>
				<tr valign="middle">
					<th scope="row"><label for="rss"><?php _e('Photo Feed', 'sspdt'); ?>
					</label>
					</th>
					<td><input id="rss" name="rss" type="checkbox" value="1"
					<?php if($defaults['rss'] == "1")  { echo "checked = 'checked'";} ?> />
					<?php _e('Show a link to a photo feed for gallery images.', 'sspdt'); ?>
					</td>
				</tr>
			</table>

			<h3>
			<?php _e('Presentation', 'sspdt'); ?>
			</h3>
			<p>
				<i><?php _e('FancyBox settings for image presentation.', 'sspdt'); ?>
				</i>
			</p>
			<table class="form-table">
				<tr valign="middle">
					<th scope="row"><label><?php _e('Box', 'sspdt'); ?> </label>
					</th>
					<td><?php _e('Padding', 'sspdt');?> <input id="padding"
						name="padding" type="text" size="2"
						value="<?php echo $fb['padding']; ?>" />px <span
						style="margin-left: 20px;"><?php _e('Margin', 'sspdt'); ?> </span>
						<input id="margin" name="margin" type="text" size="2"
						value="<?php echo $fb['margin']; ?>" />px</td>
				</tr>
				
				<tr valign="middle">
					<th><label for="fb_overlay"><?php _e('Overlay', 'sspdt'); ?> </label>
					</th>
					<td><input id="overlayShow" name="overlayShow" type="checkbox"
						value="1"
						<?php if($fb['overlayShow'] == "1")  { echo "checked = 'checked'";} ?> />
						<?php _e('Show overlay', 'sspdt'); ?> <span
						style="margin-left: 20px;"><?php _e('Opacity:', 'sspdt'); ?> </span>
						<select id="overlayOpacity" name="overlayOpacity">
							<option value="0"
							<?php if($fb['overlayOpacity'] == '0') {echo "selected";} ?>>0</option>
							<option value="0.1"
							<?php if($fb['overlayOpacity'] == '0.1') {echo "selected";} ?>>0.1</option>
							<option value="0.2"
							<?php if($fb['overlayOpacity'] == '0.2') {echo "selected";} ?>>0.2</option>
							<option value="0.3"
							<?php if($fb['overlayOpacity'] == '0.3') {echo "selected";} ?>>0.3</option>
							<option value="0.4"
							<?php if($fb['overlayOpacity'] == '0.4') {echo "selected";} ?>>0.4</option>
							<option value="0.5"
							<?php if($fb['overlayOpacity'] == '0.5') {echo "selected";} ?>>0.5</option>
							<option value="0.6"
							<?php if($fb['overlayOpacity'] == '0.6') {echo "selected";} ?>>0.6</option>
							<option value="0.7"
							<?php if($fb['overlayOpacity'] == '0.7') {echo "selected";} ?>>0.7</option>
							<option value="0.8"
							<?php if($fb['overlayOpacity'] == '0.8') {echo "selected";} ?>>0.8</option>
							<option value="0.9"
							<?php if($fb['overlayOpacity'] == '0.9') {echo "selected";} ?>>0.9</option>
							<option value="1"
							<?php if($fb['overlayOpacity'] == '1') {echo "selected";} ?>>1</option>
					</select> <span style="margin-left: 20px;"><?php _e('Color:', 'sspdt'); ?>
					</span> <input type="text" id="overlayColor" name="overlayColor"
						value="<?php echo $fb['overlayColor'];?>" size="8" />
					</td>
				</tr>
				<tr valign="middle">
					<th><label><?php _e('Navigation', 'sspdt'); ?> </label>
					</th>
					<td>
						<p>
							<input type="checkbox" id="cyclic" name="cyclic" value="1"
							<?php if($fb['cyclic'] == "1")  { echo "checked = 'checked'";} ?> />
							<?php _e('Cyclic navigation', 'sspdt'); ?>
						</p>
						<p>
							<input type="checkbox" id="showCloseButton"
								name="showCloseButton" value="1"
								<?php if($fb['showCloseButton'] == "1")  { echo "checked = 'checked'";} ?> />
								<?php _e('Show close button', 'sspdt'); ?>
						</p>
						<p>
							<input type="checkbox" id="showNavArrows" name="showNavArrows"
								value="1"
								<?php if($fb['showNavArrows'] == "1")  { echo "checked = 'checked'";} ?> />
								<?php _e('Show navigation arrows', 'sspdt'); ?>
						</p>
						<p>
							<input type="checkbox" id="enableEscapeButton"
								name="enableEscapeButton" value="1"
								<?php if($fb['enableEscapeButton'] == "1")  { echo "checked = 'checked'";} ?> />
								<?php _e('Enable ESC key', 'sspdt'); ?>
						</p>
					</td>
				</tr>
				<tr valign="middle">
					<th><label><?php _e('Transitions', 'sspdt'); ?> </label>
					</th>
					<td>
						<p>
						<?php _e('In:', 'sspdt'); ?>
							<select id="transitionIn" name="transitionIn">
								<option value="none"
								<?php if($fb['transitionIn'] == 'none') {echo "selected";} ?>>
									<?php _e('none', 'sspdt'); ?>
								</option>
								<option value="fade"
								<?php if($fb['transitionIn'] == 'fade') {echo "selected";} ?>>
									<?php _e('fade', 'sspdt'); ?>
								</option>
								<option value="elastic"
								<?php if($fb['transitionIn'] == 'elastic') {echo "selected";} ?>>
									<?php _e('elastic', 'sspdt'); ?>
								</option>
							</select> <span style="margin-left: 20px;"><?php _e('Speed:', 'sspdt'); ?>
							</span> <input id="speedIn" name="speedIn" type="text" size="4"
								value="<?php echo $fb['speedIn']; ?>" />ms <span
								style="margin-left: 20px;"><?php _e('Easing:', 'sspdt'); ?> </span>
							<select id="easingIn" name="easingIn">
								<option value="linear"
								<?php if($fb['easingIn'] == 'linear') {echo "selected";} ?>>
									<?php _e('Linear', 'sspdt'); ?>
								</option>
								<option value="swing"
								<?php if($fb['easingIn'] == 'swing') {echo "selected";} ?>>
									<?php _e('Swing', 'sspdt'); ?>
								</option>
							</select>
						</p>
						<p>
							<?php _e('Out:', 'sspdt'); ?>
							<select id="transitionOut" name="transitionOut">
								<option value="none"
								<?php if($fb['transitionOut'] == 'none') {echo "selected";} ?>>
									<?php _e('none', 'sspdt'); ?>
								</option>
								<option value="fade"
								<?php if($fb['transitionOut'] == 'fade') {echo "selected";} ?>>
									<?php _e('fade', 'sspdt'); ?>
								</option>
								<option value="elastic"
								<?php if($fb['transitionOut'] == 'elastic') {echo "selected";} ?>>
									<?php _e('elastic', 'sspdt'); ?>
								</option>
							</select> <span style="margin-left: 20px;"><?php _e('Speed:', 'sspdt'); ?>
							</span> <input id="speedOut" name="speedOut" type="text" size="4"
								value="<?php echo $fb['speedOut']; ?>" />ms <span
								style="margin-left: 20px;"><?php _e('Easing:', 'sspdt'); ?> </span>
							<select id="easingOut" name="easingOut">
								<option value="linear"
								<?php if($fb['easingOut'] == 'linear') {echo "selected";} ?>>
									<?php _e('Linear', 'sspdt'); ?>
								</option>
								<option value="swing"
								<?php if($fb['easingOut'] == 'swing') {echo "selected";} ?>>
									<?php _e('Swing', 'sspdt'); ?>
								</option>
							</select>
						</p>
						<p>
							<?php _e('Change speed:', 'sspdt'); ?>
							<input id="changeSpeed" name="changeSpeed" type="text" size="4"
								value="<?php echo $fb['changeSpeed']; ?>" />ms <i
								style="margin-left: 20px;"><?php _e('Speed of resizing when changing gallery items, in milliseconds', 'sspdt'); ?>
							</i>
						</p>
					</td>
				</tr>

			</table>


			<p class="submit">
				<input type="submit" name="submit" class="button-primary"
					value="<?php _e('Update settings', 'sspdt'); ?>" />
			</p>
		</form>

	</div>


</div>
<?php
}

/**
 * Add default options to the db on plugin activation. Don't overwrite existing options.
 */
function sspdt_default_options() {
	
	$options   = get_option('sspdt_format_options');
	$tmp       = $options;
	
	if( !isset( $options['grid_width'] ) ) $tmp['grid_width']                      = '60';
	if( !isset( $options['grid_height'] ) ) $tmp['grid_height']                    = '60';
	if( !isset( $options['grid_crop'] ) ) $tmp['grid_crop']                        = '1';
	if( !isset( $options['grid_quality'] ) ) $tmp['grid_quality']                  = '75';
	if( !isset( $options['grid_sharpen'] ) ) $tmp['grid_sharpen']                  = '1';
	
	if( !isset( $options['thumb_width'] ) ) $tmp['thumb_width']                    = '240';
	if( !isset( $options['thumb_height'] ) ) $tmp['thumb_height']                  = '240';
	if( !isset( $options['thumb_crop'] ) ) $tmp['thumb_crop']                      = '0';
	if( !isset( $options['thumb_quality'] ) ) $tmp['thumb_quality']                = '80';
	if( !isset( $options['thumb_sharpen'] ) ) $tmp['thumb_sharpen']                = '1';
	if( !isset( $options['thumb_align'] ) ) $tmp['thumb_align']                    = 'alignleft';
	if( !isset( $options['thumb_caption'] ) ) $tmp['thumb_caption']                = '1';
	if( !isset( $options['thumb_caption_format'] ) ) $tmp['thumb_caption_format']  = '%caption%';
	if( !isset( $options['thumb_watermark'] ) ) $tmp['thumb_watermark']            = '0';
	
	if( !isset( $options['large_width'] ) ) $tmp['large_width']                    = '1000';
	if( !isset( $options['large_height'] ) ) $tmp['large_height']                  = '720';
	if( !isset( $options['large_crop'] ) ) $tmp['large_crop']                      = '0';
	if( !isset( $options['large_quality'] ) ) $tmp['large_quality']                = '85';
	if( !isset( $options['large_sharpen'] ) ) $tmp['large_sharpen']                = '1';
	if( !isset( $options['large_caption_format'] ) ) $tmp['large_caption_format']  = '<div style=&quot;text-align:left&quot;><b>%caption%</b><br />%byline% (%date% in %city%, %country%)</div>';
	if( !isset( $options['large_watermark'] ) ) $tmp['large_watermark']            = '0';
	
	if( !isset( $options['date_format'] ) ) $tmp['date_format']                    = 'd.m.Y';

	update_option( 'sspdt_format_options', $tmp );
	
	$options   = get_option('sspdt_defaults');
	$tmp       = $options;
	
	if( !isset( $options['model'] ) ) $tmp['model']                    = 'gallery';
	if( !isset( $options['model_id'] ) ) $tmp['model_id']              = '1';
	if( !isset( $options['limit'] ) ) $tmp['limit']                    = '24';
	if( !isset( $options['tags'] ) ) $tmp['tags']                      = '';
	if( !isset( $options['tagmode'] ) ) $tmp['tagmode']                = 'one';
	
	if( !isset( $options['sort_on'] ) ) $tmp['sort_on']                = 'captured_on';
	if( !isset( $options['sort_direction'] ) ) $tmp['sort_direction']  = 'DESC';
	if( !isset( $options['rss'] ) ) $tmp['rss']                        = '0';

	update_option( 'sspdt_defaults', $tmp );
	
	$options   = get_option('sspdt_fancybox');
	$tmp       = $options;
	
	if( !isset( $options['padding'] ) ) $tmp['padding']                        = '10';
	if( !isset( $options['margin'] ) ) $tmp['margin']                          = '20';
	
	if( !isset( $options['titleShow'] ) ) $tmp['titleShow']                    = '1';
	if( !isset( $options['titlePosition'] ) ) $tmp['titlePosition']            = 'over';
	if( !isset( $options['counterShow'] ) ) $tmp['counterShow']                = '0';
	
	if( !isset( $options['overlayShow'] ) ) $tmp['overlayShow']                = '1';
	if( !isset( $options['overlayOpacity'] ) ) $tmp['overlayOpacity']          = '0.3';
	if( !isset( $options['overlayColor'] ) ) $tmp['overlayColor']              = '#666';
	
	if( !isset( $options['cyclic'] ) ) $tmp['cyclic']                          = '0';
	if( !isset( $options['showNavArrows'] ) ) $tmp['showNavArrows']            = '1';
	if( !isset( $options['showCloseButton'] ) ) $tmp['showCloseButton']        = '1';
	if( !isset( $options['enableEscapeButton'] ) ) $tmp['enableEscapeButton']  = '1';
	
	if( !isset( $options['transitionIn'] ) ) $tmp['transitionIn']              = '1';
	if( !isset( $options['speedIn'] ) ) $tmp['speedIn']                        = '300';
	if( !isset( $options['easingIn'] ) ) $tmp['easingIn']                      = 'linear';
	if( !isset( $options['transitionOut'] ) ) $tmp['transitionOut']            = '1';
	if( !isset( $options['speedOut'] ) ) $tmp['speedOut']                      = '400';
	if( !isset( $options['easingOut'] ) ) $tmp['easingOut']                    = 'linear';
	if( !isset( $options['changeSpeed'] ) ) $tmp['changeSpeed']                = '400';

	update_option( 'sspdt_fancybox', $tmp );
}
?>