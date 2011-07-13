<?php

function widget_sspdt_newest() {
	echo "<h2>Neueste Fotos</h2>\n<p>";
	echo sspdt_album_thumbs(0,0,8,'','','captured_on', 'DESC');
	echo "</p>\n";
}

function init_sspdt_widget() {
	register_sidebar_widget( __( "Photo Grid", "sspdt" ), "widget_sspdt_newest");
}

add_action("plugins_loaded", "init_sspdt_widget");


?>