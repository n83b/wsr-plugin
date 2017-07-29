<?php 
/*
Plugin Name: WSR plugin boilerplate
Plugin URI: http://websector.com
Description: 
Version: 1.0.0
Author: WSR
Author URI: http://websector.com
License: A short license name. Example: GPL2
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WSR_plugin{

	var $settings;

	public function __construct(){
 		//plugin settings
		$this->settings = array(
			'path'	=>  plugins_url() . '/wsr-plugin',
			'dir'	=> dirname( __FILE__ ),
			'currentUrl' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
		);

		//actions
        add_action( 'admin_init', array($this, 'check_required_plugins_exists') );
 		register_activation_hook( __FILE__, array($this, 'on_activation') );
 		register_deactivation_hook( __FILE__, array($this, 'on_deactivate') );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		add_shortcode('wsr_plugin', array($this, 'wsr_plugin_shortcode'));
		add_action( 'wp_ajax_wsr_action', array($this, 'wsr_ajax_callback' ));
		add_action( 'wp_ajax_nopriv_wsr_action', array($this, 'wsr_ajax_callback' ));
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
	    ?><div class="error"><p>WSR plugin requires the cmb2 plugin by WebDevStudios.</p></div><?php
	}



	/***********************************************************
	 *  When plugin is actived
	 */
	function on_activation(){
		//call function to create custom posts here
		flush_rewrite_rules();
	}


	/***********************************************************
	 *  When plugin is de-activated
	 */
	function on_deactivate(){

	}



	/***********************************************************
	 *  Register scritps for plugins
	 */
	function register_plugin_scripts(){
		wp_enqueue_script('jquery');
		//change these to enqueue if not restricted to shortcode
		wp_register_style( 'wsr-plugin-css', $this->settings['path'] . '/css/plugin.css' );
		wp_register_script( 'wsr-plugin-js', $this->settings['path'] . '/js/plugin.js', array('jquery'), '1', true );
	}



	/***********************************************************
	 * shortcode generic function
	 */
	function wsr_plugin_shortcode($atts){

		$a = shortcode_atts( array(
	        'element' => '#wsrplugin'
	    ), $atts );
		//$to_use = $a['element'];

		//Enqueue any scripts needed - Note, need to register the script first then enqueue here	
		wp_enqueue_style( 'wsr-plugin-css');
		wp_enqueue_script( 'wsr-plugin-js'); 
		wp_localize_script('mv-plugin-js', 'wsr_plugin_ajax', array(	
			"ajaxurl" => admin_url('admin-ajax.php'),
			"ajax_nonce" => wp_create_nonce('security-nounce-here'),
			"siteurl" => get_bloginfo('url'),
			"path" => $this->settings['path']
		));

		ob_start(); ?> 
		<?php include('wsr-plugin-view.php'); ?>
		<?php return ob_get_clean();
	}


	/***********************************************************
	 *  Form ajax callback function
	 */	
	function wsr_ajax_callback(){
		check_ajax_referer( 'security-nounce-here', 'security' );
		$result = $_POST['mydata'];

		wp_send_json_success($result);
		wp_die();
	}
}


$wsr_plugin = new WSR_plugin();








