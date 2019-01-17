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

return array(
	// Basic settings
	'hide_dot_files'			=> true,
	'list_folders_first'	=> true,
	'list_sort_order'			=> 'natcasesort',
	// WARNING: Changing "salt" after initial login will prevent matching password encryption.
	// Recommend changeing "salt" prior to creating core.db, which is created on initial login.
	'salt'								=> 'Backdoorv1.0', // Set your salt for encryption
	'api_key'							=> '3423-6545-7647', // Create your own API Key
	'timezone'						=> 'Africa/Lusaka', // Set your timezone
	'base_url'						=> 'http://code.cynojine.com/', // URL where backdoor is included
	'key_code'						=> '171674171674687071687068701716747565909089756590908967897867796869', // Default: 123 (on keypad)
	'default_email'				=> 'info@cynojine.com', // Set your email
	'save_alerts'					=> true, // This toggles popup after saving

		// Chat refresh settings
	'chat_refresh'				=> 3000, // (1000 = 1 sec) This refreshes the chat that is currently open
	'chat_log_refresh'		=> 5000, // Refresh rate for all your conversation logs
	'chat_online_refresh'	=> 4500, // Refresh rate to check whos online

	// Hidden files
	'hidden_files'		=> array(
		'.ht*',
		'*/.ht*',
		'backdoor',
		'backdoor/*',
		'backdoor.php',
		'backdoor_browser.php'
	),

	// File hashing threshold
	'hash_size_limit'	=> 268435456, // 256 MB

	// Custom sort order
	'reverse_sort'		=> array(
		// 'path/to/folder'
	)
);

?>