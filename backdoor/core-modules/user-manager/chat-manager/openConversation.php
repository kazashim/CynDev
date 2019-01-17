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

	if (isset($_POST['file']) && $_POST['file'] != "") {
		// Include time formatter
		include('timeFormatter.php');
		
		// Set necessary vars
		$convo_file = $_POST['file'];
		$convo_handler = fopen($convo_file, "a+");
		$return_convo = array();

		// Check if file has any data
		if (filesize($convo_file)) {
			$convo_raw = fread($convo_handler,filesize($convo_file));
			$convo = explode(";",$convo_raw);

			// Limit chat to 100 lines to lighten the load on the front end
			if (count($convo) > 100) {
				$convo = array_slice($convo, -100);
			}

			// Loop through array to provide chat data
			foreach ($convo as $entry) {
				if ($entry != "") {
					preg_match("/\[(.*?)\]\[(.*?)\]\[(.*?)\]/", $entry, $entry_data);
					preg_match("/\((.*)\)/", $entry, $entry_text);
					$user_id = $entry_data[1];
					$timestamp = $entry_data[2];
					$user = $entry_data[3];
					$text = $entry_text[1];

					$entry = array(
						"id" => $user_id,
						"user" => $user,
						"text" => $text,
						"time" => time_elapsed_string($timestamp)
					);

					$return_convo[] = $entry;
				}
			}
		}

		fclose($convo_handler);
		echo json_encode(array('success' => 'true','chat' => $return_convo));

	} else {
		echo json_encode(array('success' => 'false','msg' => 'File name not provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}

?>