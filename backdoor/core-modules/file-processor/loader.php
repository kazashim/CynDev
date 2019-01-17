<?php
/*
@category		PHP CMS
@package		Backdoor - Your Online Companion Editor
@author			Shannon Reca <iam@shannonreca.com>
@copyright	2015 Shannon Reca
@usage			For more specific usage see the documentation at http://backdoor.shannonreca.com
@license		http://codecanyon.net/licenses/standard
@version		build-020216 v1.3
@since			02/02/16
@feedback		Email: feedback@shannonreca.com
*/

// Secure login only
include('../user-manager/session.php');
// Initiate UserManager Class
$UserManager = require_once("../user-manager/userManager.php");

// Get file extension function.
function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

// API header check
include('../user-manager/header.php');

if ($UserManager->config['api_key'] == $header_api_key) {

	if (isset($_POST['fileLoc']) && $_POST['fileLoc'] != "") {
		// Set root directory.
		$root = dirname(dirname(dirname(dirname(__FILE__))));
		// Required: File location.
		$file_location = $root.urldecode($_POST['fileLoc']);
		// Data provided from file location.
		$ext = get_file_extension($file_location);
		$contents = file_get_contents($file_location);
		// Get file types to set correct syntax highlighting.
		$syntax_types = require_once('syntaxType.php');

		if (array_key_exists($ext,$syntax_types)) {
			$format = $syntax_types[$ext];
		} else {
			$format = "htmlmixed";
		}

		$return = array(
			"format" => $format,
			"content" => $contents
		);

		print json_encode($return);
	}

} else {
	echo json_encode(array('format' => 'plain','content' => 'Incorrect key.'));
}
?>