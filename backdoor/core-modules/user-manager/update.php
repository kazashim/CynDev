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
include('session.php');
// Initiate UserManager Class
$UserManager = require_once("userManager.php");
// API header check
include('header.php');

if ($UserManager->config['api_key'] == $header_api_key) {

	if (
		isset($_POST['user']) && $_POST['user'] != "" &&
		isset($_POST['pass']) && $_POST['pass'] != ""
		) {

		$newUser = isset($_POST['newUser'])?$_POST['newUser']:"";
		$newPass = isset($_POST['newPass'])?$_POST['newPass']:"";

		if($UserManager->updateUser($_POST['user'],$_POST['pass'],$newUser,$newPass)) {
			echo json_encode(array('success' => 'true'));
		} else {
			echo json_encode(array('success' => 'false','msg' => 'Login info update has failed.'));
		}

	} else {
		echo json_encode(array('success' => 'false','msg' => 'Values have not been provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}
?>