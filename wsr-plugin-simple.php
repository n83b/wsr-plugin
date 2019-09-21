<?php 
/*
Plugin Name: WSR Plugin
Plugin URI: 
Description: 
Version: 1.0.0
Author:Websector
Author URI: http://websector.com.au
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WsrPlugin{



	static $instance = false;



	public function __construct()
	{
		add_action( 'init', array($this, 'doStuff'), 10, 1);
	}



	public static function getInstance()
	{
		if ( !self::$instance )
			self::$instance = new self();
		return self::$instance;
	}
	


	/***********************************************************
	/* Do Stuff
	*/
	public function doStuff() 
    {   
      echo 'Stuff';
    }
	
  
}

$wsrPlugin = WsrPlugin::getInstance();
