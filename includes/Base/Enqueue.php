<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;
use \Includes\Base\BaseController;

class Enqueue extends BaseController{

	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueuePage'));
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueuePage'));
	}

	public function enqueuePage(){
		// enqueue all our scripts
		wp_enqueue_style( 'bootstrap-min-css', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css', __FILE__ );
		wp_enqueue_style( 'font-awesome-min-css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', __FILE__ );
		wp_enqueue_style( 'JqueryConfirmCSS', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css', 99 );
		wp_enqueue_style( 'jquery-ui-min-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', __FILE__ );
		wp_enqueue_style( 'Bidipluginstyle-page', $this->plugin_url . 'assets/css/pluginStyleSheet.css', 99 );
		wp_enqueue_style( 'Bidipluginstyle-page-mobile', $this->plugin_url . 'assets/css/responsive.css', 99 );
		

		wp_enqueue_script( 'jquery-1-12-4', 'https://code.jquery.com/jquery-1.12.4.min.js', __FILE__ );
		wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', __FILE__ );
		wp_enqueue_script( 'bootstrap-min-js', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', __FILE__ );
		wp_enqueue_script( 'nicescroll-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js', __FILE__ );
		wp_enqueue_script( 'JqueryConfirmCSSJS', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', __FILE__ );
		
		wp_enqueue_script( 'BidiPluginScript', $this->plugin_url . 'assets/scripts/pluginScripts.js', __FILE__ );
		wp_localize_script( 'BidiPluginScript', 'BidiPluginScript', array( 'ajax_url' => $this->plugin_url . 'includes/Pages/RecycleSubmit.php' ) );

		wp_localize_script('BidiPluginScript', 'pluginScript', array(
		    'pluginsUrl' => $this->plugin_url,
		));
		
	}

}