<?php
/*
@category		PHP CMS
@package		Backdoor - Your Online Companion Editor
@author			Shannon Reca <iam@shannonreca.com>
@copyright	2015 Shannon Reca
@usage			For more specific usage see the documentation at http://backdoor.shannonreca.com
@license		/backdoor/licenses/License-Backdoor.txt
@version		build-101415 Alpha
@since			10/14/15
@feedback		Email: feedback@shannonreca.com
*/

class DB extends SQLite3 {
	function __construct() {
		$currentDir = dirname(__FILE__);
		$this->open($currentDir.'/core.db');
	}
}

// A class specific to managing users only with SQLite.
class UserManager {

	private $db;
	public $config;

	function __construct() {
		$currentDir = dirname(__FILE__);
		$this->config = require($currentDir."/../config.php");
		date_default_timezone_set($this->config['timezone']);
		$this->db = new DB();
		$this->init();
	}

	private function encrypt($pure_string) {
		$pure_string = trim($pure_string);
		/*
		// Originally was planning on encrypting the password, however this seemed to be causing problems.
		// This is temporarily disabled.
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $this->config['salt'], utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
		*/
		return $pure_string;
	}

	public function adminCheck($userid){
		$userLogin = $this->db->prepare("SELECT * FROM Users");
		$result = $userLogin->execute();

		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			if ($row['UniqueID'] == $userid && $userid != "" && $row['ID'] == 1) {
				return true;
				break;
			}
		}

		return false;
	}

	public function userLogin($user,$pass,$setSession){
		$user = trim($user);
		$pass = $this->encrypt($pass);

		$userLogin = $this->db->prepare("SELECT * FROM Users");
		$result = $userLogin->execute();

		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			if ($row['Password'] == $pass && $pass != "") {
				if ($setSession) {
					ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/backdoor/sessions/'));
					session_start();
					setcookie("backdoorUserId", $row['UniqueID'],time()+60*60*24*30,"/");
					setcookie("backdoorUserEmail", $row['User'],time()+60*60*24*30,"/");
					setcookie("backdoorSession",session_id(),time()+60*60*24*30,"/");

					// Open online file
					$online_data = file_get_contents('chat-manager/online.json');
					$online_obj = (array) json_decode($online_data,true);

					// Update log file
					if (is_array($online_obj["users"])) {
						$online_obj["users"][] = array("id" =>  $row['UniqueID'], "user" => $row['User'], "timestamp" => time());
					} else {
						$online_obj["users"] = array();
						$online_obj["users"][] = array("id" =>  $row['UniqueID'], "user" => $row['User'], "timestamp" => time());
					}
					$onlineData = json_encode($online_obj);
					$currentOnline = fopen('chat-manager/online.json', "w");
					fwrite($currentOnline, $onlineData);
					fclose($currentOnline);
				}
				return true;
				break;
			}
		}

		return false;
	}

	public function updateUser($oldUser,$oldPass,$newUser,$newPass) {
		if ($this->userLogin($oldUser,$oldPass,false)) {
			if ($newUser == "") {
				$newUser = $oldUser;
			}
			if ($newPass == "") {
				$newPass = $oldPass;
			}
			$newPass = $this->encrypt($newPass);
			$updateUser = $this->db->prepare("UPDATE Users SET User=:newUser, Password=:newPass WHERE User=:oldUser");
			$updateUser->bindValue(':newUser',$newUser);
			$updateUser->bindValue(':newPass',$newPass);
			$updateUser->bindValue(':oldUser',$oldUser);
			$result = $updateUser->execute();
			if ($this->userLogin($newUser,$newPass,true)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function createUser($user,$userId,$pass,$isAdmin) {
		$proceed = false;

		if (is_bool($isAdmin) === true && $isAdmin === true) {
			$proceed = true;
		} else if ($isAdmin != "") {
			if ($this->adminCheck($isAdmin)) {
				$proceed = true;
			}
		}

		if ($proceed) {
			$pass = $this->encrypt($pass);
			$createUser = $this->db->prepare("INSERT INTO Users (UniqueID,User,Password) VALUES (:userid,:user,:pass)");
			$createUser->bindValue(':user',$user);
			$createUser->bindValue(':userid',$userId);
			$createUser->bindValue(':pass',$pass);
			$result = $createUser->execute();
			return $result;
		} else {
			return false;
		}
	}

	public function deleteUser($isAdmin,$uid) {
		if ($this->adminCheck($isAdmin)) {
			$deleteUser = $this->db->prepare("DELETE FROM Users WHERE UniqueID=:userid");
			$deleteUser->bindValue(':userid',$uid);
			$result = $deleteUser->execute();
			return $result;
		} else {
			return false;
		}
	}

	public function getUsers($isAdmin, $list = "") {
		if ($this->adminCheck($isAdmin)) {
			$userLogin = $this->db->prepare("SELECT * FROM Users");
			$result = $userLogin->execute();
			$list = array();

			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				if ($row['ID'] != 1) {
					$list[] = array(
						"email" => $row['User'],
						"uid" => $row['UniqueID']
					);
				}
			}

			return $list;
		} else {
			return false;
		}
	}

	public function getChatUsers($list = "") {
		if ($list != "") {
			// Look for specific users in list
			$ids = explode(",",$list);
			$query = 'SELECT * FROM Users WHERE UniqueID IN (';
			$comma = '';
			for($i=0; $i<count($ids); $i++){
				$query .= $comma.':p'.$i; // :p0, :p1, ...
				$comma = ',';
			}
			$query .= ')';
			$userLogin = $this->db->prepare($query);
			for($i=0; $i<count($ids); $i++){
			  $userLogin->bindValue(':p'.$i, $ids[$i]);
			}
		} else {
			$userLogin = $this->db->prepare("SELECT * FROM Users");
		}

		$result = $userLogin->execute();
		$list = array();

		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$list[] = array(
				"email" => $row['User'],
				"uid" => $row['UniqueID']
			);
		}

		return $list;
	}

	public function getUser($id) {
		$getUser = $this->db->prepare("SELECT * FROM Users WHERE UniqueID=:userid");
		$getUser->bindValue(':userid',$id);
		$result = $getUser->execute();

		if ($result) {
			$row = $result->fetchArray(SQLITE3_ASSOC);

			return array(
				"email" => $row['User'],
				"uid" => $row['UniqueID']
			);
		} else {
			return false;
		}
	}

	private function init() {
		$this->db->exec('CREATE TABLE IF NOT EXISTS Users (ID INTEGER PRIMARY KEY AUTOINCREMENT, UniqueID CHAR(64) NOT NULL, User CHAR(75) NOT NULL, Password CHAR(75) NOT NULL)');

		$prepare = $this->db->prepare("SELECT count(*) AS total FROM Users");
		$result = $prepare->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);

		if ($row['total'] == 0) {
			$userId = hash('sha256', $this->config['default_email'].date("Y-m-d H:m:s"));
			if ($this->createUser($this->config['default_email'],$userId,'admin',true)) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}

return new UserManager();
?>