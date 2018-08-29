<?php 
/*
Plugin Name: WSR plugin
Plugin URI: http://websector.com.au
Description: 
Version: 1.0.0
Author: WSR
Author URI: http://websector.com.au
License: A short license name. Example: GPL2


	Copyright 2013 Websektor
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

	NOTES:
	find and replace the following:
	WSR_PLUGIN
	WSR_plugin
	wsr_plugin
	wsr-plugin

	Helpers:
	File system path to plugin:  plugin_dir_path(__FILE__);
	URL to plugin:  plugins_url(__FILE__)
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if( !defined( 'WSR_PLUGIN_VER' ) )
	define( 'WSR_PLUGIN_VER', '1.0.0' );


// Start up the engine
if ( !class_exists( 'WSR_plugin' ) ) {
class WSR_plugin{


		static $instance = false;


		private function __construct(){

			//Admin
	 		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts'));

	 		//templates
	 		add_filter('single_template', array( $this, 'load_movie_template'));
	 		add_filter('archive_template', array($this, 'load_movie_archive_template'));

			//front end
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts'));
			add_shortcode('wsr_plugin', array($this, 'wsr_plugin_shortcode'));
			add_action( 'wp_ajax_wsr_action', array($this, 'wsr_ajax_callback' ));
			add_action( 'wp_ajax_nopriv_wsr_action', array($this, 'wsr_ajax_callback' ));
		}


		// --------------------------------------------------------------------
		

		/**
		 * If an instance exists, this returns it.  If not, it creates one
		 */
		public static function getInstance() {
			if ( !self::$instance )
				self::$instance = new self;
			return self::$instance;
		}


		// --------------------------------------------------------------------


		/**
		 *  Register Admin scripts
		 */
		function register_admin_scripts(){
			//if load onliy on a certain admin page
			// if ( 'edit.php' != $wsrplugin ) {
   			// 		return;
   			//	}
			wp_enqueue_style( 'wsr-plugin-admin', plugins_url('admin/css/wsr-admin.css', __FILE__), array(), WSR_PLUGIN_VER, 'all' );
		}


		// --------------------------------------------------------------------


		/**
		 *  Register front end scripts for plugins
		 */
		function register_plugin_scripts(){
			if (!is_admin()){
				wp_enqueue_script('jquery');
				//change these to enqueue if not restricted to shortcode
				wp_register_style( 'wsr-plugin-css', plugins_url('public/css/wsr-plugin.css', __FILE__), array(), WSR_PLUGIN_VER, false);
				wp_register_script( 'wsr-plugin-js', plugins_url('public/js/wsr-plugin.js', __FILE__), array('jquery'), WSR_PLUGIN_VER, true );
				//wp_enqueue_style( 'wsr-plugin-css');
				//wp_enqueue_script( 'wsr-plugin-js'); 
				//wp_localize_script('mv-plugin-js', 'wsr_plugin_ajax', array(	
				// 	"ajaxurl" => admin_url('admin-ajax.php'),
				// 	"ajax_nonce" => wp_create_nonce('security-nounce-here'),
				// 	"siteurl" => get_bloginfo('url'),
				// 	"path" => plugins_url('', __FILE__)
				// ));
			}
		}


		// --------------------------------------------------------------------
		

		/**
		 * shortcode function
		 */
		function wsr_plugin_shortcode($atts){

			$a = shortcode_atts( array(
		        'element' => '#wsr-plugin'
		    ), $atts );


			wp_enqueue_style( 'wsr-plugin-css');
			wp_enqueue_script( 'wsr-plugin-js'); 
			wp_localize_script('mv-plugin-js', 'wsr_plugin_ajax', array(	
				"ajaxurl" => admin_url('admin-ajax.php'),
				"ajax_nonce" => wp_create_nonce('security-nounce-here'),
				"siteurl" => get_bloginfo('url'),
				"path" => plugins_url('', __FILE__)
			));

			ob_start(); ?> 
			<?php include('wsr-plugin-view.php'); ?>
			<?php return ob_get_clean();
		}


		// --------------------------------------------------------------------


		/**
		 *  Form ajax callback function
		 */	
		function wsr_ajax_callback(){
			check_ajax_referer( 'security-nounce-here', 'security' );
			$result = sanitize_text_field($_POST['mydata']);

			wp_send_json_success($result);
			wp_die();
		}


		// --------------------------------------------------------------------
		


		/*
		 * For using template from plugin
		 * Allows moving template to root of theme folder for override
		 */
		function load_movie_template($template) {
		    global $post;

		    if ($post->post_type == "movie" && $template !== locate_template(array("single-movie.php"))){
		        /* This is a "movie" post 
		         * AND a 'single movie template' is not found on 
		         * theme or child theme directories, so load it 
		         * from our plugin directory
		         */
		        return plugin_dir_path( __FILE__ ) . "single-movie.php";
		    }

		    return $template;
		}




		// --------------------------------------------------------------------
		


		function load_movie_archive_template($template) {
		    global $post;

		    if (is_post_type_archive("movie") && $template !== locate_template(array("archive-movie.php"))){

		        return plugin_dir_path( __FILE__ ) . "archive-movie.php";
		    }

		    return $template;
		}



		// --------------------------------------------------------------------



		/**
		 *  Output to error log in current plugin directory
		 */	
		function error_log($msg){
			$timezone_string = get_option('timezone_string');
			date_default_timezone_set($timezone_string);

			$log = "[" . date("Y-m-d g:ia") . "] " . $msg . "\n";
			error_log($log, 3, plugin_dir_path(__FILE__) . '/debug.log');
		}

	}
}


// Instantiate our class
$WSR_plugin = WSR_plugin::getInstance();








