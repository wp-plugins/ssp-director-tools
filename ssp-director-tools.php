<?php
/*
 Plugin Name: SSP Director Tools
 Description: SSP Director Tools help you to link content from a SlideShowPro Director installation to WordPress posts and pages.
 Version: 1.3
 Text Domain: sspdt
 Author: Matthias Scheidl <dev@scheidl.name>
 */

/*  Copyright 2010-2011  Matthias Scheidl  (email : dev@scheidl.name)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $sspdt_nonce;
define('SSPDT_API_VERSION', 'api');

//helper functions
require_once 'includes/helpers.php';

//suppress error reporting by the SSPD API
error_reporting(0);


//i18n
load_plugin_textdomain( 'sspdt', false, basename(dirname(__FILE__)) . '/languages/' );

//Load styles
function add_admin_styles() {
	wp_enqueue_style('sspdt-admin-styles', plugins_url('/css/sspdt-admin.css', __FILE__));
}

add_action('wp_print_styles', 'add_sspdt_styles');
function add_sspdt_styles() {
	if(!is_admin()) {
		wp_register_style('sspdt_css', plugins_url('css/sspdt.css', __FILE__ ));
		wp_enqueue_style('sspdt_css');
	}
}

// Load Admin scripts
function add_admin_scripts() {
	wp_register_script('sspdt_admin_script', plugins_url('/js/sspdt-admin.js', __FILE__));
	wp_enqueue_script('sspdt_admin_script');
}

//Load FancyBox
add_action('wp_print_scripts', 'add_fancybox_scripts');
add_action('wp_print_styles', 'add_fancybox_styles');
add_action('wp_head', 'init_fancybox');

function add_fancybox_scripts() {
	if(!is_admin()) {
		if(!wp_script_is('jquery')) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
			wp_enqueue_script( 'jquery' );
		}
		wp_register_script('sspdt_fancybox_js', plugins_url('/js/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__));
		wp_enqueue_script('sspdt_fancybox_js');
		wp_register_script('sspdt_fancybox_easing_js', plugins_url('/js/fancybox/jquery.easing-1.3.pack.js', __FILE__));
		wp_enqueue_script('sspdt_fancybox_easing_js');
	}
}

function init_fancybox() {
	if(!is_admin()) {
		$fb = get_option('sspdt_fancybox');
		echo '<script type="text/javascript">';
		echo '	var $j = jQuery.noConflict();';
		echo '	$j(document).ready(function() {';
		echo '		$j("a.sspdt-fancybox").fancybox({"type" : "image"';

		if( isset( $fb['padding'] ) ) echo ', "padding" : ' . (int) $fb['padding'];
		if( isset( $fb['margin'] ) ) echo ', "margin" : ' . (int) $fb['margin'];
		echo ( $fb['titleShow'] === '1' ? ', "titleShow" : true' : ', "titleShow" : false');
		if( isset( $fb['titlePosition'] ) && $fb['titlePosition'] != 'outside' ) echo ', "titlePosition" : "' . $fb['titlePosition'] . '"';
		echo ( $fb['overlayShow'] === '1' ? ', "overlayShow" : true' : ', "overlayShow" : false');
		if( isset( $fb['overlayOpacity'] ) && $fb['overlayShow'] === '1') echo ', "overlayOpacity" : ' . (float) $fb['overlayOpacity'] ;
		if( isset( $fb['overlayColor'] )  && $fb['overlayShow'] === '1') echo ', "overlayColor" : "' . $fb['overlayColor'] . '"';
		echo ( $fb['cyclic'] === '1' ? ', "cyclic" : true' : ', "cyclic" : false');
		echo ( $fb['showArrows'] === '1' ? ', "showArrows" : true' : ', "showArrows" : false');
		echo ( $fb['showCloseButton'] === '1' ? ', "showCloseButton" : true' : ', "showCloseButton" : false');
		echo ( $fb['enableEscapeButton'] === '1' ? ', "enableEscapeButton" : true' : ', "enableEscapeButton" : false');

		if( isset( $fb['transitionIn'] ) ) echo ', "transitionIn" : "' . $fb['transitionIn'] . '"';
		if( isset( $fb['speedIn'] ) && $fb['transitionIn'] != 'none') echo ', "speedIn" : ' . (int) $fb['speedIn'];
		if( isset( $fb['easingIn'] ) && $fb['transitionIn'] == 'easing' ) echo ', "easingIn" : "' . $fb['easingIn'] . '"';
		if( isset( $fb['transitionOut'] ) ) echo ', "transitionOut" : "' . $fb['transitionOut'] . '"';
		if( isset( $fb['speedOut'] ) && $fb['transitionOut'] != 'none') echo ', "speedOut" : ' . (int) $fb['speedOut'];
		if( isset( $fb['easingOut'] ) && $fb['transitionOut'] == 'easing' ) echo ', "easingOut" : "' . $fb['easingOut'] . '"';
		if( isset( $fb['changeSpeed'] ) ) echo ', "changeSpeed" : ' . (int) $fb['changeSpeed'];
		
		echo ', "onStart" : function(currentArray,currentIndex,currentOpts){ 
						if(currentOpts.titlePosition != "float") {
                        var obj = currentArray[ currentIndex ]; 
                        if ($j(obj).next().length) 
                                this.title = $j(obj).next().html(); 
                        }
                } ';

		echo '		});';
		echo '	});';
		echo '</script>';
	}
}

function add_fancybox_styles() {
	if(!is_admin()) {
		wp_register_style('sspdt_fancybox_css', plugins_url('js/fancybox/jquery.fancybox-1.3.4.css', __FILE__ ));
		wp_enqueue_style('sspdt_fancybox_css');
	}
}

//Load Director API
if (!class_exists('Director')) {
	$apipath = 'includes/'. SSPDT_API_VERSION .'/DirectorPHP.php';
	require_once ($apipath);
}


//Options page
require_once 'includes/sspdt-options-page.php';
add_action( 'admin_menu', 'add_sspdt_options_page');
function add_sspdt_options_page() {
	$options_page = add_options_page( 'SSP Director Tools', 'SSP Director Tools', 'manage_options', 'ssp-director-tools', 'sspdt_options_page');
	add_action( 'admin_print_styles-' . $options_page, 'add_admin_styles' );
	add_action('admin_print_scripts-widgets.php', 'sspdt_admin_script');
}


if ( !function_exists('wp_nonce_field') ) {
	function sspdt_nonce_field($action = -1) { return; }
	$sspdt_nonce = -1;
} else {
	function sspdt_nonce_field($action = -1) { return wp_nonce_field($action); }
	$sspdt_nonce = 'sspdt-update-key';
}

//Enable photogrids
require_once 'includes/SSPDT.php';

//Shortcodes
require_once 'includes/shortcodes.php';

//Widgets
require_once('includes/SSPDT_Widget.php');
add_action( 'widgets_init', 'sspdt_register_widget' );
function sspdt_register_widget() {
	register_widget( 'SSPDT_Widget' );
}

/**
 * Plugin activation handler
 */
function sspdt_activation_handler() {
	sspdt_default_options();
}

//Hooks
register_activation_hook(__FILE__, 'sspdt_activation_handler');

?>