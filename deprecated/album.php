<?php
error_reporting(0);
include('includes/api/DirectorPHP.php');

$sspdt_api_key = get_option('sspdt_api_key');

$director = new Director('local-eddb7219727d3d8e93efd6ce351ddce0', 'director.photopica.de');
#$director->cache->set('sspdt');
#$director->cache->disable();


  $thumb = array(
     'name' => 'thumb', 
     'width' => '72', 
     'height' => '72', 
     'crop' => 1, 
     'quality' => 75, 
     'sharpening' => 1
  );

$director->format->add($thumb);
$album = $director->album->get($_GET['id']);
$contents = $album->contents[0];

foreach($contents as $content) {
	//printf("<div class='sspdt_thumb_box' id='sspdt_content-%s'>\n", $content->id);
	printf("	<img src='%s' title='%s' />\n", $content->thumb->url, utf8_decode($content->title));
	//print("</div>");
}


echo $html;

?>