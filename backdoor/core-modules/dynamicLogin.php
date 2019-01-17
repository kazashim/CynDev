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

$config = require_once('config.php');
$key_code = $config['key_code'];
$base_url = $config['base_url'];

if (preg_match("/".$key_code."/", $_POST['keyTrack'])) {
	$success = false;
	$dir = dirname(dirname(dirname(__FILE__))).'/';
	$name = time().'.html';
	$location = $dir.$name;
	$content = '<html>
		<head>
			<title>Backdoor - Your Online Companion Editor</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
			<link rel="icon" href="'.$base_url.'/backdoor/assets/images/global/favicon.ico">
			<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
			<link rel="stylesheet" href="'.$base_url.'/backdoor/assets/css/styles.css">
			<script type="text/javascript" src="'.$base_url.'/backdoor/assets/js/vendor/jquery.min.js"></script>
			<script type="text/javascript">
				var baseUrlStr = "'.$config['base_url'].'";
				var bdSession = "'.$config['api_key'].'";
			</script>
			<script type="text/javascript" src="'.$base_url.'/backdoor/assets/js/modules/processLogin.js"></script>
		</head>
		<body>
			<main role="main" class="main">
				<div class="login-box">
					<div class="login-box-logo"><img src="'.$base_url.'/backdoor/assets/images/global/bd_ver.svg"></div>
					<div id="alert"></div>
					<div class="login-box-form">
						<input id="email" type="email" name="email" placeholder="your@email.com">
						<input id="pass" type="password" name="pass" placeholder="Password">
						<button id="loginBtn" name="submit">Log In</button>
					</div>
				</div>
			</main>
		</body>
	</html>';

	$file_pointer = fopen($location,'w');
	if (fwrite($file_pointer,$content)) {
		$success = true;
		setcookie("tempLoginFile",$location,time()+60*60*24*30,"/");
	}
	fclose($file_pointer);
	chmod($location, 0777);

	if ($success) {
		echo json_encode(array('success' => 'true','url' => $base_url.'/'.$name));
	} else {
		echo json_encode(array('success' => 'false'));
	}
} else {
	echo json_encode(array('success' => 'false'));
}

?>