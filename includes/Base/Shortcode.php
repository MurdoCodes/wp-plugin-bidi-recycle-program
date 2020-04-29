<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;
use \Includes\Base\BaseController;

class Shortcode extends BaseController{
	function register() {
		add_shortcode( 'Bidi_Recycle', array( $this , 'template' ) );
	}

	function template(){
		// require admin template
		if ( is_user_logged_in() ) {
		   require_once $this->plugin_path . 'templates/page.template.php';	
		} else {
		   echo "<h1>Please Log In To Gain Access to the Bidi Recycle Program";
		}		
	}
}