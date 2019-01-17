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
include('../user-manager/session.php');
// Initiate UserManager Class
$UserManager = require_once("../user-manager/userManager.php");

// Functions for specific task
function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),0);
}

function recurse_copy($src, $dst) {
	$dir = opendir($src);
	$result = ($dir === false ? false : true);
	if ($result !== false) {
		$result = @mkdir($dst);
		if ($result === true) {
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' ) && $result) {
					if ( is_dir($src . '/' . $file) ) {
						$result = recurse_copy($src . '/' . $file,$dst . '/' . $file);
					} else { 
						$result = copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
			closedir($dir);
		}
	}
	return $result;
}

function recurse_delete($path) {
  if (is_dir($path) === true) {
    $files = array_diff(scandir($path), array('.', '..'));
    foreach ($files as $file) {
      recurse_delete(realpath($path).'/'.$file);
    }
    return rmdir($path);
  } else if (is_file($path) === true) {
    return unlink($path);
  }
  return false;
}

// API header check
include('../user-manager/header.php');

if ($UserManager->config['api_key'] == $header_api_key) {

	if (isset($_POST['callType']) && $_POST['callType'] != "" && isset($_POST['fileLoc']) && $_POST['fileLoc'] != "") {
		// Required data: Call type and file location.
		$call_type = $_POST['callType'];
		$file_location = $_POST['fileLoc'];
		// Optional data: This can be used to pass in any additional data, but may not be required in some cases.
		$misc_data = (isset($_POST['miscData']) && $_POST['miscData'] != "") ? $_POST['miscData'] : " ";

		// Set some necessary variables.
		$config = require("../config.php");
		$matches = preg_split("/browser\.php\?dir=/", $file_location);
		$root = dirname(dirname(dirname(dirname(__FILE__))));

		if (count($matches) > 1) {
			// Folder Found
			$item_type = "folder";
			$location = $root.'/'.$matches[1];
		} else {
			// File Found
			$item_type = "file";
			$location = $root.str_replace($config['base_url'], "", $file_location);
		}

		$location = urldecode($location);

		// Run requested function.
		switch ($call_type) {
			// Copy Process.
			case 'copyFile':
				$success = false;

				if ($item_type == "file") {
					if (file_exists($location)) {
						$file_name = basename($location);
						$file_ext = get_file_extension($file_name);
						$bare_name = basename($location,$file_ext);
						$new_name = $bare_name."_copy".$file_ext;
						$new_location = dirname($location);
						$copy_to = $new_location."/".$new_name;

						if (copy($location,$copy_to)) {
							$success = true;
							$extra = $copy_to;
							chmod($copy_to, 0644);
						}
					}
				} else if ($item_type == "folder") {
					if (recurse_copy($location, $location."_copy")) {
						$success = true;
						$extra = $location."_copy";
					}
				}

				if ($success) {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> $extra,
						"success"	=> true
					));
				} else {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> "",
						"success"	=> false
					));
				}
				break;

			// Rename Process. -----------------------------------------------------
			case 'renameFile':
				$success = false;

				$parent_dir = dirname($location);
				$rename_to = $parent_dir.'/'.$misc_data;

				if (rename($location, $rename_to)) {
					$success = true;
					$extra = $rename_to;
					chmod($rename_to, 0644);
				}

				if ($success) {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> $extra,
						"success"	=> true
					));
				} else {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> "",
						"success"	=> false
					));
				}
				break;

			// Delete Process. -----------------------------------------------------
			case 'deleteFile':
				$success = false;
				$extra = $location;

				if ($item_type == "file") {
					if (file_exists($location)) {
						if (unlink($location)) {
							$success = true;
						}
					}
				} else if ($item_type == "folder") {
					if (recurse_delete($location)) {
						$success = true;
					}
				}

				if ($success) {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> $extra,
						"success"	=> true
					));
				} else {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> $item_type,
						"extra"		=> "",
						"success"	=> false
					));
				}
				break;

			// New Folder Process. -----------------------------------------------------
			case 'newFolder':
				$success = false;

				$new_folder_to = $location.'/'.$misc_data;

				if (mkdir($new_folder_to)) {
					$success = true;
					$extra = $new_folder_to;
					chmod($new_folder_to, 0755);
				}

				if ($success) {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> "folder",
						"extra"		=> $extra,
						"success"	=> true
					));
				} else {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> "folder",
						"extra"		=> "",
						"success"	=> false
					));
				}
				break;

			// Save Process. -----------------------------------------------------
			case 'saveFile':
			case 'saveasFile':
				$success = false;

				$file_pointer = fopen($location,'w');
				if (fwrite($file_pointer,$misc_data)) {
					$success = true;
					$extra = $location;
				}
				fclose($file_pointer);
				chmod($location, 0644);

				if ($success) {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> "file",
						"extra"		=> $extra,
						"success"	=> true
					));
				} else {
					echo json_encode(array(
						"action"	=> $call_type,
						"item"		=> "file",
						"extra"		=> "",
						"success"	=> false
					));
				}
				break;
		}
	}

} else {
	echo json_encode(array(
		"action"	=> "unknown",
		"item"		=> "unknown",
		"extra"		=> "Incorrect key.",
		"success"	=> false
	));
}
?>