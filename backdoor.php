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

// Secure login only
include('backdoor/core-modules/user-manager/session.php');
$baseUrl = $sessConfig['base_url'];

// Delete login page if dynamically created.
if (isset($_COOKIE['tempLoginFile'])) {
	if (file_exists($_COOKIE['tempLoginFile'])) {
		unlink($_COOKIE['tempLoginFile']);
	}
	setcookie("tempLoginFile", "", time()-3600,"/");
}

$UM = require("backdoor/core-modules/user-manager/userManager.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10 lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html>
	<!--<![endif]-->
	<head>
		<title> CynCode - Your Online Companion Editor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<link rel="icon" href="<?php echo $baseUrl;?>/backdoor/assets/images/global/favicon.ico">
		<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="stylesheet" href="<?php echo $baseUrl;?>/backdoor/assets/css/font-awesome.min.css"/>
		<link rel="stylesheet" href="<?php echo $baseUrl;?>/backdoor/assets/css/styles.css"/>
		<script type="text/javascript">
			var baseUrlStr = "<?php echo $baseUrl;?>";
			var bdSession = "<?php echo $sessConfig['api_key'];?>";
			var savAlr = <?php echo ($sessConfig['save_alerts']?true:0);?>;
			var cr = <?php echo $sessConfig['chat_refresh'];?>;
			var clr = <?php echo $sessConfig['chat_log_refresh'];?>;
			var cor = <?php echo $sessConfig['chat_online_refresh'];?>;
		</script>
	</head>
	<body>
		<div id="feedback" class="feedback">
			<button class="feedback-tab">Feedback</button>
			<div class="feedback-form">
				<p>Please send any feedback to improve Backdoor.</p>
				<input type="hidden" name="fromEmail" value="code@cynojine.com">
				<textarea name="feedback"></textarea>
				<button class="feedback-send-button">Send Feedback</button>
			</div>
		</div>
		<header class="header">
			<div class="header-logo"><img src="<?php echo $baseUrl;?>/backdoor/assets/images/global/bd_icon.svg"></div>
			<ul id="headerMenu" class="header-menu">
				<li>
					<button>File</button>
					<ul>
						<li>
							<button data-menu-id="newFile">New</button>
						</li>
						<li>
							<button data-context-call="saveFile">Save</button>
						</li>
						<li>
							<button data-context-call="saveasFile">Save As</button>
						</li>
						<?php if ($UM->adminCheck($_COOKIE["backdoorUserId"])){?>
						<li>
							<button data-menu-id="editConfig">Configurations</button>
						</li>
						<?php }?>
						<li>
							<a href="<?php echo $baseUrl;?>/backdoor/core-modules/user-manager/logout.php">Close Backdoor</a>
						</li>
					</ul>
				</li>
				<li>
					<button>User Management</button>
					<ul>
						<li>
							<button data-menu-id="updatePass">Update Login</button>
						</li>
						<?php if ($UM->adminCheck($_COOKIE["backdoorUserId"])){?>
						<li>
							<button data-menu-id="addUser">Add User</button>
						</li>
						<li>
							<button data-menu-id="deleteUser">Delete User</button>
						</li>
						<?php }?>
					</ul>
				</li>
				<li>
					<button>Help</button>
					<ul>
						<li>
							<a href="backdoor/Documentation.pdf" target="_blank">Documentation</a>
						</li>
					</ul>
				</li>
			</ul>
		</header>
		<main role="main" class="main">
			<div class="col-tools">
				<ul id="toolNav" class="tool-nav">
          <li>
            <button data-menu-id="new-file"><i class="fa fa-file-code-o"></i></button>
          </li>
          <li>
            <button data-menu-id="browser"><i class="fa fa-files-o"></i></button>
          </li>
          <li>
            <button data-menu-id="upload"><i class="fa fa-upload"></i></button>
          </li>
          <li>
            <button data-menu-id="chat"><i class="fa fa-comment"></i></button>
          </li>
        </ul>
				<div class="tool-viewer">
					<div id="fileBreadcrumbs" class="file-breadcrumbs"></div>
					<div class="file-browser">
						<div id="fileBrowser"></div>
					</div>
					<div class="file-uploader">
						<form id="fileUpload" method="post" action="<?php echo $baseUrl;?>/backdoor/core-modules/mini-upload/upload.php" enctype="multipart/form-data" class="file-uploader-form">
							<div id="drop" class="file-uploader-drop-zone">Drop Here <a>Browse</a>
								<input type="file" name="upl" multiple=""> </div>
							<input id="currentDir" type="hidden" name="dir" value="">
							<ul class="file-uploader-list"></ul>
						</form>
					</div>
				</div>
			</div>
			<div class="col-interactive">
				<div class="interactive-tabs">
					<ul id="tabViews"> </ul>
				</div>
				<div id="views" class="interactive-viewer"> </div>
			</div>
			<div class="col-chat">
				<div id="chatBox" class="chat">
					<div class="chat-users">
						<div class="chat-users-scrollview">
							<div class="chat-content"></div>
						</div>
					</div>
					<div class="chat-list-to-convo">
						<div class="chat-lists">
							<div class="chat-header">Conversations</div>
							<div class="chat-entries">
								<div class="chat-entries-scrollview">
									<div class="chat-content"></div>
								</div>
							</div>
						</div>
						<div class="chat-convo">
							<div class="chat-header">
								<button><i class="fa fa-chevron-left"></i></button>Chat View </div>
							<div class="chat-entries">
								<div class="chat-entries-scrollview">
									<div class="chat-content"></div>
								</div>
								<div class="chat-text">
									<textarea placeholder="Write your message here..."></textarea>
									<button><i class="fa fa-chevron-right"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<footer class="footer"></footer>
		<div id="contextMenu" class="context-menu"></div>
		<div id="dialogBoxes">
			<div id="userLogin" class="dialog-box">
				<p>Update your login information.</p>
				<input id="ULemail" type="email" value="<?php echo $_COOKIE["backdoorUserEmail"]?>" required>
				<input id="ULpass" type="password" placeholder="" required>
				<input id="ULnewEmail" type="email" placeholder="New Email">
				<input id="ULnewPass" type="password" placeholder="New Password">
				<button class="dialog-box-cancel">Cancel</button>
				<button id="ULupdate">Update</button>
			</div>
			<div id="addUser" class="dialog-box">
				<p>Add User to Backdoor.</p>
				<input id="AUemail" type="email" placeholder="useremail@domain.com" required>
				<input id="AUpass" type="password" placeholder="Password" required>
				<button class="dialog-box-cancel">Cancel</button>
				<button id="AUadd">Add</button>
			</div>
			<div id="deleteUser" class="dialog-box">
				<p>Delete User from Backdoor.</p>
				<ul class="dialog-box-list"></ul>
				<button class="dialog-box-cancel">Cancel</button>
			</div>
		</div>
		<div id="contextDisable" class="context-disable"></div>
		<script src="<?php echo $baseUrl;?>/backdoor/assets/js/vendor/svg4everybody.min.js"></script>
		<script src="<?php echo $baseUrl;?>/backdoor/assets/js/vendor/require.js"></script>
		<script src="<?php echo $baseUrl;?>/backdoor/assets/js/config.js"></script>
	</body>
</html>