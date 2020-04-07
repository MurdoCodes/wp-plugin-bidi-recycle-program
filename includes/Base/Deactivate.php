<?php
/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class Deactivate{
	public static function deactivate(){
		flush_rewrite_rules();
	}
}