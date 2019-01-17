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

$sessConfig = require("../config.php");
session_start();

// Open online file
$online_data = file_get_contents('chat-manager/online.json');
$online_obj = (array) json_decode($online_data,true);

// Remove user
$i = 0;
$users = $online_obj["users"];
while ($i < count($users)) {
	if ($users[$i]['id'] == $_COOKIE["backdoorUserId"]) {
		unset($users[$i]);
	}
	$i++;
}
$online_obj["users"] = $users;

// Update log file
$onlineData = json_encode($online_obj);
$currentOnline = fopen('chat-manager/online.json', "w");
fwrite($currentOnline, $onlineData);
fclose($currentOnline);

setcookie("backdoorUserId", "", time()-3600,"/");
setcookie("backdoorUserEmail", "", time()-3600,"/");;
setcookie("backdoorSession", "", time()-3600,"/");
setcookie("tempLoginFile", "", time()-3600,"/");
$_COOKIE = array();
$_SESSION = array();
session_destroy();
header("location: ".$sessConfig['base_url']);
?>