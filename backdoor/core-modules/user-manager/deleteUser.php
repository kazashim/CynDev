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

	if (isset($_POST['uid']) && $_POST['uid'] != "") {

		if($UserManager->deleteUser($cookie_user_id,$_POST['uid'])) {
			echo json_encode(array('success' => 'true'));
		} else {
			echo json_encode(array('success' => 'false','msg' => 'Deleting user has failed.'));
		}

	} else {
		echo json_encode(array('success' => 'false','msg' => 'Missing values.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}
?>