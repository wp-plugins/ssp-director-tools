<?php


function sspdt_preview_box(){
	wp_nonce_field( plugin_basename(__FILE__), 'sspdt_nonce' );
	echo ("Image ID: ");
	$custom_fields = get_post_custom();
	$my_custom_field = $custom_fields['director_id'];
	 foreach ( $my_custom_field as $key => $value )
	    echo $key . " => " . $value . "<br />";
}

function sspdt_selector_box(){
	wp_nonce_field( plugin_basename(__FILE__), 'sspdt_nonce' );

	$director = new sspdt();
	$albums = $director->album->all(array('list_only' => true, 'only_published' => true, 'only_active' => true));
	echo "Album wählen: <select id= 'sspdt_album_select' onchange='sspdt_requestAlbumContent(this)'>";
	echo "<option value='0'>— Wähle —</option>";
	foreach($albums as $album) {
		echo "<option value='". $album->id ."'>" . utf8_decode($album->name). "</option>";
	}
	echo "</select>";
	echo "<div id='sspdt_album_content'></div>";
}

?>