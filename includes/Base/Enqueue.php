<?php 

/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/

namespace Includes\Base;

use \Includes\Base\BaseController;

class Enqueue extends BaseController{

	public function register() {
		// add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdmin'));
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueuePage'));	
	}

	// public function enqueueAdmin(){
	// }

	public function enqueuePage(){
		// enqueue all our scripts
		wp_enqueue_style( 'bootstrap-min-css', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css', __FILE__ );
		wp_enqueue_style( 'font-awesome-min-css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', __FILE__ );
		wp_enqueue_style( 'mypluginstyle-page', $this->plugin_url . 'assets/css/pluginStyleSheet.css', 99 );

		wp_enqueue_script( 'jquery-1-12-4', 'https://code.jquery.com/jquery-1.12.4.min.js', __FILE__ );
		wp_enqueue_script( 'bootstrap-min-js', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', __FILE__ );
		wp_enqueue_script( 'nicescroll-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js', __FILE__ );
		wp_enqueue_script( 'mypluginscritpt-page', $this->plugin_url . 'assets/scripts/pluginScripts.js', __FILE__ );
	}

}