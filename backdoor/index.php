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


/*
NOTE: THIS IS AN OPTIONAL DIRECT LOGIN PAGE. USE THIS IF YOU PREFER NOT HAVING A DYNAMICALLY CREATED LOGIN PAGE.
*/

$config = include('core-modules/config.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>CynDev - Your Online Companion Editor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<link rel="icon" href="assets/images/global/favicon.ico">
		<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="robots" content="noindex, nofollow">
		<link rel="stylesheet" href="assets/css/styles.css">
		<style>
			body {
				background-color: #1E1E1E;
				color: #AAAAAA;
				font-family: 'Open Sans', sans-serif;
				padding: 0 40px;
				margin: 0 auto;
				max-width: 720px;
			}
			a, a:hover {
				color: #ff004a;
			}
		</style>
		<script type="text/javascript" src="assets/js/vendor/jquery.min.js"></script>
		<script type="text/javascript">
			var baseUrlStr = "<?php echo $config['base_url'];?>";
			var bdSession = "<?php echo $config['api_key'];?>";
		</script>
		<script type="text/javascript" src="assets/js/modules/processLogin.js"></script>
	</head>
	<body>
		<main role="main" class="main">
			<div class="login-box">
				<div class="login-box-logo"><img src="assets/images/global/PM.jpeg"></div>
				<div id="alert"></div>
				<div class="login-box-form">
					<input id="email" type="email" name="email" placeholder="your@email.com">
					<input id="pass" type="password" name="pass" placeholder="Password">
					<button id="loginBtn" name="submit">Log In</button>
				</div>
			</div>
		</main>
	</body>
</html>