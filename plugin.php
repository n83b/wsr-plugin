<?php 
/*
Plugin Name: WSR myplugin boilerplate
Plugin URI: http://websector.com
Description: 
Version: 1.0.0
Author: WSR
Author URI: http://websector.com
License: A short license name. Example: GPL2
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//settings
$settings = array(
	'path'	=>  plugins_url() . '/wsr-myplugin',
	'dir'	=> dirname( __FILE__ ),
	'currentUrl' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
);

//hooks
register_activation_hook( __FILE__, 'wsr_activation');
register_deactivation_hook(__FILE__, 'wsr_deactivation');
	
//functions
function wsr_activation(){

}

function wsr_deactivation(){

}