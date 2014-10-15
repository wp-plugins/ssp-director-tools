<?php

require_once 'api/DirectorPHP.php';
require_once 'config.php';

class PhotoFeed extends Director {

	public $formats;
	public $api_path;

	public function __construct($key, $path, $preview, $full) {
		parent::__construct ( $key, $path , true );
		$this->add_formats($preview, $full);
		$this->api_path = $path;
		$this->cache->set('sspdt');
	}


	private function add_formats($preview, $full) {
			
		$this->format->add($preview);
		$this->format->add($full);
		$this->format->preview($preview);
		$this->formats['preview'] = $preview;
		$this->formats['full'] = $full;

	}

	public function rss($opts) {

		if(!isset($opts)) return;

		$options = array();

		$options['only_images'] = '1';

		if($opts['model'] == "gallery" || $opts['model'] == "album") {
			$scope = array ($opts['model'], (int) $opts['model_id']);
			$options['scope'] = $scope;
		}

		if((int) $opts['limit'] > 0) {
			$options['limit'] = $opts['limit'];
		}

		if($opts['tags'] != "") {
			$options['tags'] = array($opts['tags'], $opts['tagmode']);
		}

		if($opts['sort_on'] != "null") {
			$options['sort_on'] = $opts['sort_on'];
			$options['sort_direction'] = $opts['sort_direction'];
		}

		if($opts['sort_on' == "random"]) {
			return;
		}

		//$this->debug($options);

		$contents = $this->content->all($options);

		if($opts['model'] == 'gallery') {
			$bulk = $this->gallery->get($opts['model_id']);
		}elseif($opts['model'] == 'album') {
			$bulk = $this->album->get($opts['model_id']);
		}
		$title        = $this->prep($bulk->name);
		$description  = $this->prep($bulk->description);
		$created      = date('r', (int) $bulk->created );
		$modified     = date('r', (int) $bulk->modified );
		$protocol     = $_SERVER['HTTPS'] != "" ? 'https://' : 'http://';

		header('Content-type: application/rss+xml');

		print("<?xml version='1.0' encoding='UTF-8' ?>\n");
		print("<rss version='2.0'
		xmlns:dc='http://purl.org/dc/elements/1.1/' 
		xmlns:media='http://search.yahoo.com/mrss/' 
		xmlns:georss='http://www.georss.org/georss' 
		xmlns:atom='http://www.w3.org/2005/Atom'>\n");
		print("	<channel>\n");
		printf("		<title>%s</title>\n", $title );
		printf("		<link>%s</link>\n", 'http://' . $this->api_path);
		printf("		<description>%s</description>\n", $description );
		printf("		<lastBuildDate>%s</lastBuildDate>\n", $modified );
		printf("		<pubDate>%s</pubDate>\n", $created );
		printf("		<generator>%s</generator>\n", "SSP Director Tools – WordPress Plugin");
		printf("		<atom:link href='%s' rel='self' type='application/rss+xml' />\n", $protocol . urlencode( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ));

		foreach ($contents as $content){
				
			$description = "<img src='".$content->preview->url."' /><p>". ( $content->caption ? $this->prep($content->caption) : $this->prep($content->iptc->caption) ) ."</p>";
			$url         = $content->full->url;
			$filesize    = $content->filesize;
			$guid        = 'http://' . $this->api_path . '/content/' . $content->id;
				
			printf("		<item>\n");
			printf("			<title>%s</title>\n", $content->title ? ($content->title) : $content->src);
			printf("			<description>%s</description>\n", $this->prep($description));
			printf("			<pubDate>%s</pubDate>\n", date('r', (int) $content->created));
			if( $content->exif->latitude != ''  && $content->exif->longitude != '') {
				printf("			<georss:point>%s %s</georss:point>\n", $content->exif->latitude, $content->exif->longitude);
			}
			printf("			<enclosure url='%s' length='%s' type='image/jpeg'></enclosure>\n", $url, $filesize);
			printf("			<guid>%s</guid>\n", $guid);
			printf("			<media:title type='plain'>%s</media:title>\n", $this->prep($content->iptc->title));
			printf("			<media:description type='html'>%s</media:description>\n", $this->prep($description ) );
			printf("			<media:thumbnail url='%s' />\n", $content->preview->url);
			printf("			<media:content url='%s' fileSize='%s' type='image/jpeg'/>\n", $url, $filesize);
			printf("			<media:keywords>%s</media:keywords>\n", $this->prep($content->iptc->keywords));
			printf("			<media:credit role='photographer'>%s</media:credit>\n", $this->prep($content->iptc->byline));
			printf("			<dc:date.Taken>%s</dc:date.Taken>\n", date('c', (int) $content->captured_on));
			printf("		</item>\n");
				
		}

		print("	</channel>\n");
		print("</rss>\n");
	}

	private function prep($string) {
		return  ( htmlspecialchars( $string, ENT_QUOTES, 'UTF-8', false ) );
	}

	private function debug( $var ) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

}

?>