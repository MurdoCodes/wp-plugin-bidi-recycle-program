<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class BaseController {
	public $plugin_path;
	public $plugin_url;
	public $plugin;
	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		// C:\wamp64\www\vapor.dev\wp-content\plugins\bidi-recylce-program/
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		// http://localhost/vapor.dev/wp-content/plugins/bidi-recylce-program/
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) . '/bidi-recycle-program.php' );
		// bidi-recylce-program/bidi-recycle-program.php		
	}
}