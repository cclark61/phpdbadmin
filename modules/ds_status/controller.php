<?php
//****************************************************************
/**
* phpDBAdmin
*
* @package		phpDBAdmin
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//****************************************************************

load_plugin('POP_remote_conn');

switch ($this->action) {

	default:
		include('main.php');
		break;
}

?>
