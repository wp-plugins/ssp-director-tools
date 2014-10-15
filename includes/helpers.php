<?php

// print variable
function debug_var( $var ) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

// print error message
function sspdt_settings_error ($msg){
	printf('<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', $msg);
}


//sanitize positive integer
function sspdt_posint($val) {
	return abs( (int) $val);
}

//sanitize boolean
function sspdt_bool($val) {
	return $val == "1" ? "1" : "0";
}

//sanitize alignment
function sspdt_align($val) {
	$haystack = array("alignnone", "alignleft", "alignright", "aligncenter");
	if (in_array($val, $haystack, true)) {
		return $val;
	} else {
		return "alignnone";
	}
}

//sanitize model
function sspdt_model($val) {
	$haystack = array("null", "album", "gallery");
	if(in_array($val, $haystack, true ) ){
		return $val;
	} else {
		return "null";
	}
}

//sanitize text
function sspdt_nohtml($val) {
	return wp_kses($val, array());
}

//sanitize html formats
function sspdt_html_format($val) {
	$allowed = array(
		"div"     => array(
			"style"  => array(), 
			"align"  => array()
		),
		"p"       => array("style" => array()),
		"b"       => array(),
		"i"       => array(),
		"strong"  => array(),
		"em"      => array(),
		"br"      => array()
	);
	return wp_kses($val, $allowed);
}

//sanitize tagmode
function sspdt_tagmode($val) {
	$haystack = array("all", "one");
	if(in_array($val, $haystack, true) ) {
		return $val;
	} else {
		return "all";
	}
}

//sanitize sort_on
function sspdt_sort_on($val) {
	$haystack = array("null", "created_on", "captured_on", "modified_on", "filename", "random");
	if(in_array($val, $haystack, true) ) {
		return $val;
	} else {
		return "null";
	}
}

//sanitize sort_direction
function sspdt_sort_direction($val) {
	$haystack = array("ASC", "DESC");
	if(in_array($val, $haystack, true) ) {
		return $val;
	} else {
		return "ASC";
	}
}

//sanitize fb title position
function sspdt_fb_title_position($val) {
	$haystack = array("outside", "inside", "over");
	if(in_array($val, $haystack, true ) ) {
		return $val;
	} else {
		return "outside";
	}
}

//sanitize fb overlay opacity
function sspdt_fb_opacity($val) {
	$haystack = array("0", "0.1", "0.2", "0.3", "0.4", "0.5", "0.6", "0.7", "0.8", "0.9", "1");
	if(in_array($val, $haystack, true)) {
		return $val;
	} else {
		return "0";
	}
}

//sanitize color
function sspdt_color($val) {
	$valid = preg_match('/#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})+$/', $val);
	if($valid) {
		return $val;
	} else {
		return "#000";
	}
}

//sanitize transition
function sspdt_fb_transition($val) {
	$haystack = array("none", "fade", "elastic");
	if(in_array($val, $haystack, true ) ) {
		return $val;
	} else {
		return "none";
	}
}

//sanitize easing
function sspdt_fb_easing($val) {
	$haystack = array("swing", "linear");
	if(in_array($val, $haystack, true ) ) {
		return $val;
	} else {
		return "linear";
	}
}

?>