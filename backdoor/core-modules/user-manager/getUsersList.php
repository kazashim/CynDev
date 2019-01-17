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

	// Get users list
	$list = $UserManager->getUsers($cookie_user_id);

	if ($list === false) {
		echo json_encode(array('success' => 'false','msg' => 'Unable to get user list.'));
	} else {
		echo json_encode(array('success' => 'true','list' => $list));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}
?>