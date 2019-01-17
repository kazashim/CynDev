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

error_reporting(E_ALL);
ini_set('display_errors',1);

// Initiate UserManager Class
$UserManager = require_once("userManager.php");
// API header check
include('header.php');

if ($UserManager->config['api_key'] == $header_api_key) {

	if (
		isset($_POST['user']) && $_POST['user'] != "" &&
		isset($_POST['pass']) && $_POST['pass'] != ""
		) {

		if($UserManager->userLogin($_POST['user'],$_POST['pass'],true)) {
			echo json_encode(array('success' => 'true','url' => $UserManager->config['base_url'].'/backdoor.php'));
		} else {
			echo json_encode(array('success' => 'false','msg' => 'Login has failed.'));
		}

	} else {
		echo json_encode(array('success' => 'false','msg' => 'Values have not been provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}
?>