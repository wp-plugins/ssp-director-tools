<?php

require_once 'includes/Encryption.php';
require_once 'includes/PhotoFeed.php';
require_once 'includes/config.php';
include_once 'includes/Testing.php';


/**
 * Helper function for turning the options string into an array of key=>value pairs
 * @param string $del1 first delimiter
 * @param string $del2 second delimiter
 * @param string $array the string to be tuned into an array
 * @return array
 */
function doubleExplode ($del1, $del2, $array){

	$array1 = explode("$del1", $array);
	foreach($array1 as $key=>$value){

		$array2 = explode("$del2", $value);
		foreach($array2 as $key2=>$value2){
			$array3[] = $value2;
		}
	}
	$afinal = array();

	for ( $i = 0; $i <= count($array3); $i += 2) {
		if($array3[$i]!=""){
			$afinal[trim($array3[$i])] = trim($array3[$i+1]);
		}
	}

	return $afinal;
}

// check if the decrypted options match the checksum
$crypt = new Encryption( $sspdt_secret );
$decrypted_options = $crypt->decode($_GET['p']);

if(md5($decrypted_options) != $_GET['c']) die('Incorrect feed parameters.');

$options    = doubleExplode('&', '=', $decrypted_options);
$feed       = new PhotoFeed( $sspd_api_key, $sspd_api_path, $sspd_feed_preview, $sspd_feed_full);

$feed->rss($options);


?>