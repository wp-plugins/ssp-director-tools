<?php

include('includes/api/DirectorPHP.php');

$director = new Director('local-eddb7219727d3d8e93efd6ce351ddce0', 'director.photopica.de');
$albums = $director->album->all(array('list_only' => true, 'only_published' => true, 'only_active' => true));
/*
$html = "Album wählen: <select id= 'sspdt_album_select' onchange='sspdt_requestAlbumContent(this)'>";
$html.= "<option value='0'>— Wähle —</option>";
foreach($albums as $album) {
	$html .= "<option value='". $album->id ."'>" . utf8_decode($album->name). "</option>";
}
$html.= "</select>";
$html.= "<div id='sspdt_album_content'></div>";

echo $html;
*/
echo json_encode($albums);



?>