<?php
//*****************************************************************************
//*****************************************************************************
/**
* phpDBAdmin Class Plugin
*
* @package		phpDBAdmin
* @subpackage	Plugins
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//*****************************************************************************
//*****************************************************************************

class phpDBAdmin
{

    //*****************************************************************************
    //*****************************************************************************
    // Build Module List Method
    //*****************************************************************************
    //*****************************************************************************
    public static function BuildModuleList(&$modules_list, $curr_dir, $index='')
    {
    	//------------------------------------------------------
    	// Pull Directory Listing
    	//------------------------------------------------------
    	$dir_list = scandir($curr_dir);
    	if (!$dir_list) { return false; }
    
    	//------------------------------------------------------
    	// Process Each Directory Item
    	//------------------------------------------------------
    	foreach ($dir_list as $item) {
    
    		//---------------------------------------------------
    		// Skip anything that starts with "."
    		//---------------------------------------------------
    		if ($item[0] == '.') { continue; }
    
    		//---------------------------------------------------
    		// Directories Only...
    		//---------------------------------------------------
    		if (is_dir("{$curr_dir}/{$item}")) {
    
    			//-----------------------------------------------
    			// Create New Index
    			//-----------------------------------------------
    			if ($index) {
    				$new_index = $index . '/' . $item;
    			}
    			else {
    				$new_index = $item;
    			}
    
    			//-----------------------------------------------
    			// Validate Module
    			//-----------------------------------------------
    			if (!file_exists("{$curr_dir}/{$item}/local.inc.php")) { continue; }
    			include("{$curr_dir}/{$item}/local.inc.php");
    			if (empty($mod_title)) { continue; }
    			$modules_list[$item] = array(
    				'index' => $new_index
    			);
    
    			//-----------------------------------------------
    			// Save Module Variables
    			//-----------------------------------------------
    			$vars = get_defined_vars();
    			foreach ($vars as $var_name => $var_value) {
    				if (substr($var_name, 0, 4) == 'mod_') {
    					$modules_list[$item][$var_name] = $var_value;
    				}
    			}
    
    			//-----------------------------------------------
    			// Recursively Search for Sub-modules
    			//-----------------------------------------------
    			$modules_list[$item]['sub-modules'] = array();
    			self::BuildModuleList($modules_list[$item]['sub-modules'], "{$curr_dir}/{$item}");
    		}
    	}
    }

}
