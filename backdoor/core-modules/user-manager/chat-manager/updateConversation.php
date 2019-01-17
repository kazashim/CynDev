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
include('../session.php');
// Initiate UserManager Class
$UserManager = require_once("../userManager.php");
// API header check
include('../header.php');

if ($UserManager->config['api_key'] == $header_api_key) {

	if (
		isset($_POST['file']) && $_POST['file'] != "" &&
		isset($_POST['uid']) && $_POST['uid'] != "" &&
		isset($_POST['user']) && $_POST['user'] != "" &&
		isset($_POST['text']) && $_POST['text'] != ""
	) {

		$convo_file = $_POST['file'];
		$uid = $_POST['uid'];
		$time = time();
		$user = urldecode($_POST['user']);
		$text = nl2br(urldecode($_POST['text']));

		// Check if file has any data
		if (file_exists($convo_file)) {

			$convo_raw = file_get_contents($convo_file);

			$convo_raw .= "[".$uid."][".$time."][".$user."](".$text.");";

			$convo = fopen($convo_file, "w+");
			fwrite($convo, $convo_raw);
			fclose($convo);

			echo json_encode(array('success' => 'true','msg' => 'Chat has been updated.'));
		} else {
			echo json_encode(array('success' => 'false','msg' => 'File does not exist.'));
		}

	} else {
		echo json_encode(array('success' => 'false','msg' => 'Data not provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}

?>