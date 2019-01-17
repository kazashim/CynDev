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

$currentDir = dirname(__FILE__);
$sessConfig = require($currentDir."/../config.php");
$logout = false;

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/backdoor/sessions/'));
session_start();

if (!isset($_COOKIE["backdoorSession"])) {
	$logout = true;
} else {
	if (session_id() != $_COOKIE["backdoorSession"]) {
		$logout = true;
	}
}

if ($logout) {
	setcookie("backdoorUserId", "", time()-3600,"/");
	setcookie("backdoorUserEmail", "", time()-3600,"/");;
	setcookie("backdoorSession", "", time()-3600,"/");
	setcookie("tempLoginFile", "", time()-3600,"/");
	$_COOKIE = array();
	$_SESSION = array();
	session_destroy();
	header("location: ".$sessConfig['base_url']);
} else {
	$cookie_user_id = $_COOKIE["backdoorUserId"];
	$cookie_user_email = $_COOKIE["backdoorUserEmail"];
}
?>