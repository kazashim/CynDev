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

	if (isset($_POST['uid']) && $_POST['uid'] != "" && isset($_POST['wuid']) && $_POST['wuid'] != "") {
		// Set all necessary vars
		$uid = $_POST['uid'];
		$with_uid = $_POST['wuid'];
		$cid = time();
		$convo_file = $cid.'.txt';

		// Create conversation file
		if ($handle = fopen($convo_file, 'w')) {
			fclose($handle);

			// Open log file
			$log_data = file_get_contents('log.json');
			$log_obj = (array) json_decode($log_data,true);

			// Check if user has log entry
			if (array_key_exists($uid,$log_obj)) {
				// Add to existing history
				$log_obj[$uid][$cid] = array("file" => $convo_file, "members" => array($with_uid));
			} else {
				// Add and create log history
				$log_obj[$uid] = array($cid => array("file" => $convo_file, "members" => array($with_uid)));
			}

			// Repeat history process but for the other user
			if (array_key_exists($with_uid,$log_obj)) {
				$log_obj[$with_uid][$cid] = array("file" => $convo_file, "members" => array($uid));
			} else {
				$log_obj[$with_uid] = array($cid => array("file" => $convo_file, "members" => array($uid)));
			}

			// Update log file
			$newData = json_encode($log_obj);
			$downloadData = fopen("log.json", "w");
			fwrite($downloadData, $newData);
			fclose($downloadData);

			echo json_encode(array('success' => 'true','file' => $convo_file));

		} else {
			echo json_encode(array('success' => 'false','msg' => 'Could not create file.'));
		}

	} else {
		echo json_encode(array('success' => 'false','msg' => 'User id not provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}
?>