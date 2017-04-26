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

//'path'	=>  plugins_url() . '/wsr-myplugin',
//'dir'	=> dirname( __FILE__ ),



register_activation_hook( __FILE__, 'wsr_activation');
function wsr_activation(){

}



register_deactivation_hook(__FILE__, 'wsr_deactivation');
function wsr_deactivation(){

}


add_action( 'wp_enqueue_scripts', 'wsr_myplugin_scripts');
function wsr_myplugin_scripts(){
	wp_enqueue_script('jquery');
	wp_register_style( 'wsr-productsearch-css', plugins_url() . '/wsr-myplugin/wsrps.css' );
	wp_register_script( 'wsr-productsearch-js', plugins_url() . '/wsr-myplugin/wsrps.js', array('jquery'), '20170426', true );
}



add_shortcode('wsr_myplugin', 'wsr_myplugin_scripts_shortcode');
function wsr_myplugin_scripts_shortcode(){
	//Enqueue any scripts needed   
	//Note, need to register the script first then enqueue here	
	wp_enqueue_style( 'wsr-productsearch-css');
	wp_enqueue_script( 'wsr-productsearch-js'); 

	ob_start(); ?>
	<p>html goes here</p>
	<?php return ob_get_clean();
}

