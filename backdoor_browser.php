<?php
/*
@category   PHP CMS
@package    Backdoor - Your Online Companion Editor
@author     Shannon Reca <iam@shannonreca.com>
@copyright  2015 Shannon Reca
@usage      For more specific usage see the documentation at http://backdoor.shannonreca.com
@license    http://codecanyon.net/licenses/standard
@version    build-020216 v1.3
@since      02/02/16
@feedback   Email: feedback@shannonreca.com
*/

error_reporting(E_ALL);
ini_set('display_errors',1);

// Initiate UserManager Class
$config = require_once("backdoor/core-modules/config.php");
date_default_timezone_set($config['timezone']);

// API header check
include('backdoor/core-modules/user-manager/header.php');

if ($config['api_key'] == $header_api_key) {

  // Include the DirectoryLister class
  require_once('backdoor/core-modules/directory-lister/DirectoryLister.php');

  // Initialize the DirectoryLister object
  $Lister = new DirectoryLister();

  // Set default directory
  $default_dir = dirname(getcwd());

  // Restrict access to current directory
  ini_set('open_basedir', $default_dir);

  // Return file hash
  if (isset($_GET['hash'])) {

  // Get file hash array and JSON encode it
  $hashes = $Lister->getFileHash($_GET['hash']);
  $data   = json_encode($hashes);

  // Return the data
  die($data);
  }

  // Initialize the directory array
  if (isset($_GET['dir'])) {
    $dir_array = $Lister->listDirectory($_GET['dir']);
  } else {
    $dir_array = $Lister->listDirectory('.');
  }

  $breadcrumbs = $Lister->listBreadcrumbs();
  $sys_messages = $Lister->getSystemMessages();

  if (!$sys_messages) {
    $sys_messages = null;
  }

  $browser_data = array(
    'breadcrumbs' => $breadcrumbs,
    'messages' => $sys_messages,
  );

  $files_array = array();

  foreach ($dir_array as $name => $file_info) {
    if (is_file($file_info['file_path'])) {
      $is_folder = false;
    } else {
      $is_folder = true;
    }

    $files_array[] = array(
      'filename' => $name,
      'icon' => $file_info['icon_class'],
      'url' => $file_info['url_path'],
      'path' => $file_info['file_path'],
      'size' => $file_info['file_size'],
      'lastmodified' => $file_info['mod_time'],
      'sort' => $file_info['sort'],
      'handler' => $file_info['handler'],
      'isfolder' => $is_folder
    );
  }

  $browser_data['files'] = $files_array;

  $json = json_encode($browser_data);

  echo $json;

}
?>