<?php
//=======================================================================
//=======================================================================
// "Pseudo" Content Devlivery Network
//=======================================================================
//=======================================================================
//print "<pre>";print_r($_SERVER);print "</pre>";

//*****************************************************************
// Validate that user is not trying to call script directly
//*****************************************************************
if (!defined('MAIN_CONTROLLER')) {
	print "This script cannot be called directly.";
	header("HTTP/1.0 404 Not Found");
	exit;
}

//*****************************************************************
// Path Info
//*****************************************************************
$path_info = (isset($_SERVER['REQUEST_URI'])) ? ($_SERVER['REQUEST_URI']) : (false);

//*****************************************************************
// Is the request trying to use the CDN?
//*****************************************************************
if ($path_info && substr($path_info, 0, 5) == '/cdn/') {
	$cdn_file = dirname(__FILE__) . '/modules/' . substr($path_info, 4);

	//=============================================================
	// Does File Exist?
	//=============================================================
	if (file_exists($cdn_file) && !is_dir($cdn_file)) {

		//*************************************************************
		// File Info / Extension
		//*************************************************************
		$file_info = pathinfo($cdn_file);
		$extension = strtolower($file_info['extension']);

		switch ($extension) {

			//======================================================
			// Javascript
			//======================================================
			case 'js':
				header('Content-type: text/javascript');
				break;

			//======================================================
			// CSS
			//======================================================
			case 'css':
				header('Content-type: text/css');
				break;

			//======================================================
			// Images
			//======================================================
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'png':
				header("Content-type: image/{$file_info['extension']}");
				break;

			//======================================================
			// Scalable Vector Graphics (SVG)
			//======================================================
			case 'svg':
			case 'svgz':
				header("Content-type: image/svg+xml");
				if ($extension == 'svgz') {
					header("Content-Encoding: gzip");	
				}
				break;

			//======================================================
			// XML
			//======================================================
			case 'xml':
			case 'xsl':
				header('Content-type: text/xml');
				break;

			//======================================================
			// HTML / XHTML
			//======================================================
			case 'html':
			case 'xhtml':
				header('Content-type: text/html');
				break;

			//======================================================
			// Text
			//======================================================
			case 'txt':
				header('Content-type: text/plain');
				break;

			//======================================================
			// Default: File Not Found (i.e. 404)
			//======================================================
			default:
				header("HTTP/1.0 404 Not Found");
				print "File Not Found";
				exit;
				break;
		}

		//*************************************************************
		// Output Content
		//*************************************************************
		print file_get_contents($cdn_file);
	}
	//*************************************************************
	// File Does NOT exist. Send 404.
	//*************************************************************
	else {
		header("HTTP/1.0 404 Not Found");
		print "File Not Found";
	}
	exit;
}

?>
