<?php
//************************************************************
/**
* phpDBAdmin
*
* @package		phpDBAdmin
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//************************************************************

//=========================================================================
// Autoloader
//=========================================================================
spl_autoload_register('\phpOpenFW\Framework\Core::load_plugin');

//=========================================================================
// Globalize Methods
//=========================================================================
\phpOpenFW\Helpers\Globalize::All(['Utility' => 1]);

