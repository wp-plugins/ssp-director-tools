<?php

require_once 'Encryption.php';

/**
 * Class handles API calls to Director Server. Subclasses Director class.
 * @author Matthias Scheidl <dev@scheidl.name>
 *
 */
class SSPDT extends Director{

	private $format_options;
	public $formats;


	public function __construct( $api_key = '', $path = '', $debug = false, $format_options ) {

		parent::__construct ( $api_key, $path, $debug );
		$this->format_options = $format_options;
		$this->add_formats($format_options);
		
	}


	/**
	 * Adds the needes formats as defined on the plugin options page.
	 * @param array $format_options The format options as defined in the plugin settings
	 * @return void
	 */
	private function add_formats($format_options) {

		$grid = array(
		     'name' => 'grid', 
		     'width' => $format_options['grid_width'], 
		     'height' => $format_options['grid_height'], 
		     'crop' => ($format_options['grid_crop'] == '1' ? 1 : 0), 
		     'quality' => $format_options['grid_quality'], 
		     'sharpening' => ($format_options['grid_sharpen'] == '1' ? 1 : 0)
		);

		$thumb = array(
		     'name' => 'thumb', 
		     'width' => $format_options['thumb_width'], 
		     'height' => $format_options['thumb_height'], 
		     'crop' => ($format_options['thumb_crop'] == '1' ? 1 : 0), 
		     'quality' => $format_options['thumb_quality'], 
		     'sharpening' => ($format_options['thumb_sharpen'] == '1' ? 1 : 0)
		);

		$large = array(
		     'name' => 'large', 
		     'width' => $format_options['large_width'], 
		     'height' => $format_options['large_height'], 
		     'crop' => ($format_options['large_crop'] == '1' ? 1 : 0), 
		     'quality' => $format_options['large_quality'], 
		     'sharpening' => ($format_options['large_sharpen'] == '1' ? 1 : 0)
		);

		$this->format->add($grid);
		$this->format->add($thumb);
		$this->format->add($large);

		// Make our formats accessible more easily
		$this->formats['grid'] = $grid;
		$this->formats['thumb'] = $thumb;
		$this->formats['large'] = $large;


	}

	/**
	 * Creates a grid of photos
	 * @param array $content_options Options for the content to display (which content to get from Director
	 * @param string $context post|widget
	 * @return string The HTML code
	 * @todo look into the caching if random sorting is selected
	 */
	public function photogrid($content_options, $context) {

		$options = array();
		$params= "";

		//show images, only
		$options['only_images'] = '1';
		$params .= "&only_images=1";

		if($content_options['model'] == "gallery" || $content_options['model'] == "album") {
			$scope = array ($content_options['model'], (int) $content_options['model_id']);
			$options['scope'] = $scope;
			$params .= "&model=" . $content_options['model'];
			$params .= "&model_id=" . $content_options['model_id'];
		}

		if((int) $content_options['limit'] > 0) {
			$options['limit'] = $content_options['limit'];
			$params .= "&limit=" . $content_options['limit'];
		}

		if($content_options['tags'] != "") {
			$options['tags'] = array($content_options['tags'], $content_options['tagmode']);
			$params .= "&tags=" . $content_options['tags'];
			$params .= "&tagmode=" . $content_options['tagmode'];
		}

		if($content_options['sort_on'] != "null") {
			$options['sort_on'] = $content_options['sort_on'];
			$options['sort_direction'] = $content_options['sort_direction'];
			$params .= "&sort_on=" . $content_options['sort_on'];
			$params .= "&sort_direction=" . $content_options['sort_direction'];
		}

		if($content_options['sort_on' == "random"]) {
			$this->cache->disable();
		}

		if($context == 'post') {
			$rel = "post-" . get_the_ID();
		} elseif ( $context == 'widget') {
			$rel = "widget";
		}

		$params = substr($params, 1);

		$feed_options = get_option('sspdt_feed_options');
		$baseurl = $feed_options['feed_url'] . 'feed.php';
		$secret = $feed_options['secret'];

		$crypt = new Encryption($secret);
		$checksum = md5($params);

		$feedurl = $baseurl . '?p=' . $crypt->encode($params) . '&c=' . $checksum;

		$contents = $this->content->all($options);

		$out = ( "\n<div class='sspd_grid' style='display:block;margin-top:6px;'>\n" );

		foreach( $contents as $content ) {
			$width =  (int)$content->grid->width;
			$height = (int)$content->grid->height;
			$title = ($content->caption) ? $this->prep($content->caption) : $this->prep($content->iptc->caption);
			$alt = ($content->caption) ? $this->prep($content->caption) : $this->prep($content->iptc->caption);
			$out .= sprintf ("<a class='%s' rel='%s' href='%s' title='%s'>
	<img class='sspdt_grid' src='%s' alt='%s' width='%s' height='%s' type='image/jpeg'/>
</a>\n", "fancybox", $rel, $content->large->url, $title, $content->grid->url, $alt, $width, $height);
		}

		$out .= ( "</div>\n" );
		if($content_options['rss'] == '1' || $content_options == 'yes' && $content_options['sort_on'] != 'random') {
			$iconurl = plugins_url("images/rss.png", dirname( __FILE__ ));
			$out .= "<div class='sspdt_feedlink' ><img src='$iconurl' align='middle' /><a href='". $feedurl ."' >". __('Aquire these images as photo feed.', 'sspdt') . "</a></div>";
		}
		$out .= "\n";


		return $out;
	}

	/**
	 *	Creates a single content item (image)
	 *	@param integer $image A unique Director content ID
	 *	@param string $align attribute for image alignment: left|center|right
	 *	@param string $showcaption attribute determines if captions shall be shown: yes|no
	 *	@param string|integer $post_id The WP post ID
	 *	@return string The HTML code
	 */
	public function single($image, $align, $showcaption, $post_id) {

		$content = $this->content->get($image);
		$large = $content->large->url;
		$thumb = $content->thumb->url;
		$title = $content->caption ? $this->prep($content->caption) : "";

		$width =  (int)$content->thumb->width;
		$height = (int)$content->thumb->height;

		$captionwidth = $width + 2 * 5;

		$rel = "post-" . $post_id;
		$alt = $content->caption ? $this->prep($content->caption) : "";

		if($showcaption) {
			$caption = ($content->caption) ? $this->prep($content->caption) : $this->prep($content->iptc->caption);
				
			if($caption != "") {
				return sprintf("<div id='sspdt-content-%s' class='wp-caption %s' style='width:%spx'>
					<a class='%s' href='%s' rel='%s' title='%s'>
						<img src='%s' alt='%s' width='%s' height='%s' />
					</a>
					<p class='wp-caption-text'>%s</p>
				</div>", 
				$image, $align, $captionwidth, "fancybox", $large, $rel, $title, $thumb, $alt, $width, $height, $caption);
			}
		}

		return sprintf("<a class='%s sspdt_thumb' href='%s' rel='%s' title='%s'>
			<img class='%s' src='%s' alt='%s' width='%s' height='%s' />
		</a>", 
		"fancybox", $large, $rel, $title, $align, $thumb, $alt, $width, $height);

	}

	/**
	 * Prepares a string for HTML output
	 * @param string $s
	 * @return string
	 */
	private function prep($s) {
		return  ( htmlspecialchars( $s, ENT_QUOTES, 'UTF-8', false ) );
	}


}

?>