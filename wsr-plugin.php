<?php 
/*
Plugin Name: WSR plugin
Plugin URI: http://websector.com.au
Description: Updated plugin starter uses WP API and native js
Version: 1.0.0
Author: WSR
Author URI: http://websector.com.au
License: A short license name. Example: GPL2

	NOTES:
	find and replace the following:
	Myplugin 
	myplugin

	HELPERS:
	plugin_dir_path(__FILE__);
	plugins_url(__FILE__)
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


$myplugin_config = array(
	'api_key' => 'cd64539dd19283cdcc637f2ccddcd45-us6'
);


if ( !class_exists( 'Myplugin' ) ) {

//Include ACF
include_once( plugin_dir_path(__FILE__) . '/include/acf/acf.php' );


class Myplugin{

		static $instance = false;

		private function __construct($config){
			$this->api_key = $config['api_key'];

			//enqueue scripts admin
	 		//add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts'));

	 		//enqueue scripts frontend
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts'));

			//register custom post type
			//add_action('init', array($this, 'register_myplugin_cpt'));

			//register tax
           	//add_action('init', array($this, 'register_myplugin_tax'));
			
			//shortcode
			//add_shortcode('myplugin', array($this, 'myplugin_shortcode'));

			//Output custom fields via WP API
			//add_filter("rest_prepare_myplugin", array($this, 'rest_prepare_myplugin'), 10, 3);
		}


		// --------------------------------------------------------------------
		

		/**
		 * If an instance exists, this returns it.  If not, it creates one
		 */
		public static function getInstance($config) {
			if ( !self::$instance )
				self::$instance = new self($config);
			return self::$instance;
		}


		// --------------------------------------------------------------------


		/**
		 *  Register Admin scripts
		 */
		function register_admin_scripts(){
			//if load onliy on a certain admin page
			// if ( 'edit.php' != $wsrplugin ) { return; }
			wp_enqueue_style( 'myplugin-admin', plugins_url('', __FILE__), array(), 'v1.1', 'all' );
		}


		// --------------------------------------------------------------------


		/**
		 *  Register front end scripts for plugins
		 */
		function register_plugin_scripts(){
			if (!is_admin()){
				wp_register_style( 'myplugin-css', plugins_url('', __FILE__), array(), 'v1.1', false);
				wp_register_script( 'myplugin-js', plugins_url('public/js/myplugin.js', __FILE__), array(), 'v1.1', true );
				//wp_enqueue_style( 'myplugin-css');
				//wp_enqueue_script( 'myplugin-js'); 
			}
		}


		// --------------------------------------------------------------------


		function register_myplugin_cpt(){
        
            $labels = array(
                'name'	=> 'Myplugins',
                'singular_name'	=> 'Myplugin',
                'add_new_item'	=> 'Add New Myplugin',
                'edit_item'	=> 'Edit Myplugin',
                'new_item'	=> 'New Myplugin',
                'view_item'	=> 'View Myplugin',
                'search_items'	=> 'Search Myplugins',
                'not_found'	=> 'No Myplugins found',
                'not_found_in_trash' => 'No Myplugins found in trash',
                'all_items' => 'All Myplugins',
                'archives' => 'Myplugin Archives',
                'insert_into_item' => 'Insert into Myplugin',
                'uploaded_to_this_item' => 'Upload into this Myplugin'
            );
        
            register_post_type( 'cobh_myplugin', array(
                'label'		=> 'Myplugins',
                'labels'	=> $labels,
                'description'	=> 'Myplugin entries',
                'public'	=> true,
                'has_archive'	=> true,
                'show_ui' => true,
                'show_in_nav_menus'	=> true,
                'show_in_rest' => true,
                'menu_icon'	=> 'dashicons-admin-home',
                'taxonomies'	=> array('wsr_custom_tax'),
                'rewrite'	=> array('slug' => 'myplugin'),
                'supports'	=> array('title', 'editor', 'thumbnail'),
            ));
        
            flush_rewrite_rules(true);
        }


        // --------------------------------------------------------------------


        function register_myplugin_tax(){
                $taxonomy_object_types = array(
                    'cobh_myplugin'
                );

                $taxonomy_args = array(
                    'show_ui' 		=> true,
                    'show_in_rest' => true,
                    'rest_base'    => 'myplugin_tax',
                    'show_admin_column' => true,
                    'hierarchical' 	=> true,
                    'label' 		=> 'Myplugin Tax',
                    'rewrite'	=> array('slug' => 'myplugin-tax')
                );

            register_taxonomy('myplugin_tax', $taxonomy_object_types, $taxonomy_args);
        }


        // --------------------------------------------------------------------
		

		/**
		 * shortcode function
		 */
		function myplugin_shortcode($atts){

			$a = shortcode_atts( array(
		        'element' => '#myplugin'
		    ), $atts );


			wp_enqueue_style( 'myplugin-css');
			wp_enqueue_script( 'myplugin-js'); 
			wp_localize_script('myplugin-js', 'myplugin_ajax', array(	
				"ajaxurl" => admin_url('admin-ajax.php'),
				"ajax_nonce" => wp_create_nonce('security-nounce-here'),
				"siteurl" => get_bloginfo('url'),
				"path" => plugins_url('', __FILE__)
			));

			ob_start(); ?> 

			output stuff here

			<?php return ob_get_clean();
		}


		// --------------------------------------------------------------------


		function rest_prepare_myplugin($data, $post, $request) {
			$_data = $data->data;

			$params = $request->get_params();
            //if single program, then get the custom fields
           // if ( isset( $params['id'] ) ) {
			$fields = get_fields($post->ID);
			foreach ($fields as $key => $value){
				$_data[$key] = get_field($key, $post->ID);
			}
			$data->data = $_data;
           // }

			return $data;
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
$Myplugin = Myplugin::getInstance($myplugin_config);








