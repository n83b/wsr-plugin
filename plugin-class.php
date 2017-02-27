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

class WSR_myplugin{

	var $settings;

	public function __construct(){
 		//plugin settings
		$this->settings = array(
			'path'	=>  plugins_url() . '/wsr-myplugin',
			'dir'	=> dirname( __FILE__ ),
			'currentUrl' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
		);

		//actions
        add_action( 'admin_init', array($this, 'check_required_plugins_exists') );
 		register_activation_hook( __FILE__, array($this, 'on_activation') );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		add_filter( 'single_template', array($this, 'override_single_template' ));
		add_filter( 'archive_template', array($this, 'override_archive_template' ));
		add_shortcode('wsr_myplugin', array($this, 'wsr_myplugin_query'));
	}



	/***********************************************************
	 * Checks if cmb2 plugin is available, else plugin wont activate
	 */
	function check_required_plugins_exists() {
	    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'cmb2/init.php' ) ) {
	        add_action( 'admin_notices', array($this, 'display_plugin_not_loaded_notice' ));
	        deactivate_plugins( plugin_basename( __FILE__ ) ); 
	        if ( isset( $_GET['activate'] ) ) {
	            unset( $_GET['activate'] );
	        }
	    }
	}



	/***********************************************************
	 *  Outputs error message if CMB2 is not available
	 */
	function display_plugin_not_loaded_notice(){
	    ?><div class="error"><p>WSR myplugin requires the cmb2 plugin by WebDevStudios.</p></div><?php
	}



	/***********************************************************
	 *  When plugin is actived
	 */
	function on_activation(){
		//call function to create custom posts here
		flush_rewrite_rules();
	}



	/***********************************************************
	 *  Register scritps for plugins
	 */
	function register_plugin_scripts(){
		wp_enqueue_script('jquery');
		if ( is_singular( 'wsr_myplugin_post_type' ) ) {
			wp_enqueue_style( 'wsr-myplugin-css', $this->settings['path'] . '/myplugin.css' );
			wp_enqueue_script( 'wsr-myplugin-js', $this->settings['path'] . '/myplugin.js', array('jquery'), '20160102', true );
		}
	}



	/***********************************************************
	 *  To use myplugin theme file for single instead of themes
	 */
	function override_single_template( $template ){
		global $post;
		$found = locate_template('single-wsr_myplugin_post_type.php');
		 if($post->post_type == 'wsr_myplugin_post_type' && $found == ''){
	        $template = $this->settings['dir']  . '/single-wsr_myplugin_post_type.php';
	    }
	    return $template;
	}


	/***********************************************************
	 *  To use plugin theme file for single instead of themes
	 */
	public function override_archive_template($template){
		global $post;
		$found = locate_template('archive-wsr_myplugin_post_type.php');
		 if($post->post_type == 'wsr_myplugin_post_type' && $found == ''){
	      	return plugin_dir_path(__FILE__) . '/archive-wsr_myplugin_post_type.php';
	    }
	  	return $template;
	}



	/***********************************************************
	 * shortcode generic function
	 */
	function wsr_myplugin_query($atts){

		$a = shortcode_atts( array(
	        'element' => '#myplugin'
	    ), $atts );
		//$to_use = $a['element'];

		//Enqueue any scripts needed    	
		//wp_enqueue_script( 'thickbox');

		//output html
			ob_start(); ?> 
			<p>html goes here</p>
			<!-- add lightbox -->
			<script> jQuery(document).ready(function(){ jQuery("a").nivoLightbox(); });</script>
		<?php return ob_get_clean();
	}
}


$wsr_myplugin = new WSR_myplugin();








