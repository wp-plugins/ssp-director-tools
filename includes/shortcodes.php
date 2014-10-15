<?php

add_shortcode( 'sspd', 'sspdt_filter_content' );

/**
 * Shortcut for the 'sspd' tag. Creates HTML code for Director photo grids or single images
 * @author Matthias Scheidl <dev@scheidl.name>
 * @param array $atts The attributes of the sspd tag
 * @return string The HTML code
 */
function sspdt_filter_content($atts) {
	extract( shortcode_atts( array(
			'image'          => '',
			'align'          => '',
			'caption'        => '',
			'gallery'        => '',
			'album'          => '',
			'limit'          => '',
			'tags'           => '',
			'tagmode'        => '',
			'sort_on'        => '',
			'sort_direction' => '',
			'rss'            => ''
			), $atts ) );

			//helper info
			$presentation_ops = get_option('sspdt_helpers');

			//Do not filter if we are on a search result page
			if(get_search_query() == "")
			{
				$sspdt = new SSPDT(get_option('sspdt_api_key'), get_option('sspdt_api_path'), false, get_option('sspdt_format_options'));
				
				if(get_option('sspdt_api_cache')) {
					$sspdt->cache->set('sspdt');
				}
					
				if($image == '') { //we make a photo grid with multiple images
						
					$defaults  = get_option('sspdt_defaults');
					$options   = array();

					// Determine the scope of the request
					if($gallery != '') {
						$options['model'] = 'gallery';
						$options['model_id'] = $gallery;
					} elseif ($album != '') {
						$options['model'] = 'album';
						$options['model_id'] = $album;
					} elseif ($defaults['model'] =='gallery' || $defaults['model'] == 'album') {
						$options['model'] = $defaults['model'];
						$options['model_id'] = $defaults['model_id'] != '' ? $defaults['model_id'] : 1;
					}


					// Determine the maximum number of images to show
					if($limit != '0' && $limit != '') {
						$options['limit'] = $limit;
					} elseif ($defaults['limit'] != '0' && $defaults['limit'] != '') {
						$options['limit'] = $defaults['limit'];
					}

					// Determine if we do tag filtering
					if($tags != '') {
						if(strtolower($tagmode) == 'all' || strtolower($tagmode) == 'one') {
							$options['tags'] = $tags;
							$options['tagmode'] = strtolower($tagmode);
						} elseif ($defaults['tagmode'] == 'all' || $defaults['tagmode'] == 'one') {
							$options['tags'] = $tags;
							$options['tagmode'] = $defaults['tagmode'];
						} else {
							$options['tags'] = $tags;
							$options['tagmode'] = 'one';
						}
					} elseif ($defaults['tags'] != '') {
						$options['tags'] = $defaults['tags'];
						$options['tagmode'] = $defaults['tagmode'];
					}

					// Determine the sort policy
					if($sort_on != '' && $sort_on != 'null') {
						$options['sort_on'] = $sort_on;
					} elseif ($defaults['sort_on'] != '' && $defaults['sort_on'] != 'null') {
						$options['sort_on'] = $defaults['sort_on'];
					}

					// Determine the sort direction
					if(strtoupper($sort_direction) == 'ASC' || strtoupper($sort_direction) == 'DESC') {
						$options['sort_direction'] = $sort_direction;
					} elseif ($defaults['sort_direction'] == 'ASC' || $defaults['sort_direction'] == 'DESC'){
						$options['sort_direction'] = $defaults['sort_direction'];
					} else {
						$options['sort_direction'] = 'DESC';
					}

					// Show RSS feed?
					if($options['sort_on'] != 'random') {
						// show rss feed only if our gallery is not randomised
						if( $rss == 'yes' || $rss === '1' || $rss == 'no' || $rss === '0') {
							$options['rss'] = $rss;
						} elseif ( $defaults['rss'] == 'yes' || $defaults['rss'] === '1' ) {
							$options['rss'] = '1';
						} else {
							$options['rss'] = '0';
						}
							
					}


					//debug_var($options);

					return $sspdt->photogrid($options, 'post');

				} else { //we make a single image

					$format_options    = get_option('sspdt_format_options');

					// Get the plugin settings
					$showcaption       = isset($format_options['thumb_caption']) ? $format_options['thumb_caption'] : false;
					$thumbalign        = isset($format_options['thumb_align']) ? $format_options['thumb_align'] : 'alignnone';

					// Override alignment settings if align attribute provided
					if($align == 'left') $thumbalign = 'alignleft';
					if($align == 'center') $thumbalign = 'aligncenter';
					if($align == 'right') $thumbalign = 'alignright';

					// Override caption settings if caption attribute is set to 'yes' or '1'
					if($caption == 'yes' || $caption == '1') {
						$showcaption = true;
					} elseif ($caption == 'no' || $caption == '0') {
						$showcaption = false;
					}

					//post ID
					$post_id = get_the_ID();

					return $sspdt->single($image, $thumbalign, $showcaption, $post_id);
				}
			}


}

?>