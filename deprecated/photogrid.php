<?php


function sspdt_album_thumbs($galleryID, $albumID, $limit, $tags, $tagmode, $sort_on, $sort_direction) {
	//$director = new sspdt();
	$director = new Director(get_option('sspdt_api_key'), get_option('sspdt_api_path'), true);
	if($director->dead) echo "";
	
	$format_options = get_option('sspdt_format_options');
	
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

	$director->format->add($grid);
	$director->format->add($large);
	$model = $galleryID == '0' ? 'album' : 'gallery';
	$id = $galleryID == '0' ? $albumID : $galleryID;
	$scope = array($model, $id);
	$tagarray = array($tags, $tagmode);
	
	$options = array();
	$options["only_images"] = true;
	$options["sort_on"] = $sort_on;
	$options["sort_direction"] = $sort_direction;
	
	if($galleryID != "0" || $albumID != "0")$options["scope"] = $scope;
	$options["limit"] = $limit;
	if($tags != ''){
		$options["tags"] = $tagarray;
	}

	$contents = $director->content->all($options);
	
	$out = "\n<div class='sspd_grid' style='display:block'>\n";

	foreach($contents as $content) {

		$out .= "<a class='highslide' onclick='return hs.expand(this)' href='".$content->large->url."'><img class='sspd_grid' src='". $content->grid->url ."' title='". utf8_decode($content->title) ."' /></a>\n";

	}
	$out .= "</div>\n";
	
	
	return $out;
}

function sspdt_photogrid($model, $model_id, $limit, $tags, $tagmode, $sort_on, $sort_direction) {
	
	$director = new Director(get_option('sspdt_api_key'), get_option('sspdt_api_path'), true);
	$format_options = get_option('sspdt_format_options');
	
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

	$director->format->add($grid);
	$director->format->add($large);
	
	$options = array();
	
	if($model == "gallery" || $model == "album") {
		$scope = array ($model, (int) $model_id);
		$options['scope'] = $scope;
	}
	
	if((int) $limit > 0) $scope['limit'] = $limit;
	
	if($tags != "") {
		$tagarray = array($tags, $tagmode);
		$options['tags'] = $tagarray;
	}
	
	if($sort_on != "null") {
		$options['sort_on'] = $sort_on;
		$options['sort_direction'] = $sort_direction;
	}
	
	$contents = $director->content->all($options);
	
	$out = "\n<div class='sspd_grid' style='display:block'>\n";

	foreach($contents as $content) {

		$out .= "<a class='highslide' onclick='return hs.expand(this)' href='".$content->large->url."'><img class='sspd_grid' src='". $content->grid->url ."' title='". utf8_decode($content->title) ."' /></a>\n";

	}
	$out .= "</div>\n";
	
	
	return $out;
}

?>