<?php
//=========================================================================
//=========================================================================
// Login Include File
//=========================================================================
//=========================================================================

//=========================================================================
// Autoloader
//=========================================================================
spl_autoload_register('\phpOpenFW\Framework\Core::load_plugin');

//************************************************************
// Set default data source
//************************************************************
if (isset($_SESSION['default_data_source'])) {
	\phpOpenFW\Framework\Core::default_data_source($_SESSION['default_data_source']);
}

//************************************************************
// Plugin Folders
//************************************************************
if (isset($_SESSION['add_plugin_folder'])) {
	\phpOpenFW\Framework\Core::set_plugin_folder($_SESSION['file_path'] . '/' . $_SESSION['add_plugin_folder']);
}

//************************************************************
// Server Side Validation Template
//************************************************************
$_SESSION['ssv_template'] = __DIR__ . '/templates/ssv_messages.xsl';

//************************************************************
// Build a List of Modules
//************************************************************
$modules_list = array();
phpDBAdmin::BuildModuleList($modules_list, __DIR__);
$_SESSION['modules_list'] = $modules_list;

//************************************************************
// Redirect
//************************************************************
header("Location: /");
exit;
