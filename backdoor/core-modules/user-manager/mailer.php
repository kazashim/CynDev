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

	if (isset($_POST['fromEmail']) && $_POST['fromEmail'] && isset($_POST['feedback']) && $_POST['feedback']) {
		$from_email = $_POST['fromEmail'];
		$feedback = $_POST['feedback'];
		$feedback += "\n\n PHP Version: ".phpversion();
		$domain = substr(strrchr($from_email, "@"), 1);

		$to = "feedback@shannonreca.com";
		$subject = "Backdoor Feedback";
		$headers = 'From: '.$from_email."\r\n".
		'Reply-To: '.$from_email."\r\n".
		'X-Mailer: PHP/'.phpversion();

		if (filter_var($from_email, FILTER_VALIDATE_EMAIL) && checkdnsrr($domain, 'MX')) {

			if (mail($to,$subject,$feedback,$headers)) {
				echo json_encode(array('success' => 'true'));
			} else {
				echo json_encode(array('success' => 'false','message' => 'There was an error sending feedback.'));
			}

		} else {
			echo json_encode(array('success' => 'false','message' => 'Email was not valid.'));
		}

	} else {
		echo json_encode(array('success' => 'false','message' => 'Feedback was not provided.'));
	}

} else {
	echo json_encode(array('success' => 'false','msg' => 'Incorrect key.'));
}

?>