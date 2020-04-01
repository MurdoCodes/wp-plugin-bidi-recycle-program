<?php 

/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/

namespace Includes\Base;

use \Includes\Base\BaseController;

class Settings extends BaseController {

	public function register(){
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ){
		// add custom settings link
		$settings_link = '<a href="admin.php?page=bidi_recycle_program">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}