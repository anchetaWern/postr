<?php
session_start();
require_once('conn.php');
require_once('../libs/Bcrypt.php');

$bcrypt = new Bcrypt(15);

$action = $_POST['action'];

switch($action){
	case 'sign_up':
		$email 		= $_POST['email'];
		$password	= $_POST['pword'];
		
		$salt = $bcrypt->getSalt(); 
		$hash = $bcrypt->hash($password, $salt);
		
		if($query = $db->prepare("INSERT INTO tbl_users SET email = ?, hashed_password = ?, salt = ?")){
			$query->bind_param("sss", $email, $hash, $salt);
			$query->execute();
			$uid = $query->insert_id;
			
			//create default settings for new user
			$select_networks = $db->query("SELECT network FROM tbl_networks");
			if($select_networks->num_rows > 0){
				while($row = $select_networks->fetch_object()){
	
					$network = $row->network;
					$db->query("INSERT INTO tbl_settings SET network = '$network', uid = '$uid', status = 0");
				}
			}

			echo $uid;
		}
	
	break;
	
	case 'login':
		$email 		= $db->real_escape_string($_POST['email']);
		$password	= $db->real_escape_string($_POST['pword']);
		
		$select_salt = $db->query("SELECT salt FROM tbl_users WHERE email = '$email'");
		if($select_salt->num_rows > 0){
			$row = $select_salt->fetch_object();
			$salt = $row->salt;
			
			$hash = $bcrypt->hash($password, $salt);
			
			$select_user = $db->query("SELECT uid FROM tbl_users WHERE email = '$email' AND hashed_password = '$hash'");
			if($select_user->num_rows > 0){
				$row = $select_user->fetch_object();
				$uid = $row->uid;
				$_SESSION['uid'] = $uid; 
				$_SESSION['email'] = $email;
				
				echo $uid;
			}
		}
		
	break;

	case 'load_settings':
		
		//$settings = $db->query("");
	break;

	case 'create_fb_settings':
		$user_id = $_SESSION['uid'];
		$fb_type = $_POST['type'];
		$fb_id	= $_POST['fb_id'];
		$fb_name = $_POST['fb_name'];

		$db->query("
			INSERT INTO tbl_fbsettings SET uid = '$user_id', 
			fb_type = '$fb_type', fb_id = '$fb_id', fb_name = '$fb_name'
		");

	break;

	case 'update_settings':
		$user_id = $_SESSION['uid'];
		$network = $_POST['network'];
		$network_status = $_POST['status'];

		$db->query("
			UPDATE tbl_settings SET status = '$network_status'
			WHERE network = '$network' AND uid = '$user_id'
		");

	break;

	case 'update_fbsetting':
		$user_id = $_SESSION['uid'];
		$fb_id = $_POST['fb_id'];
		$fb_status = $_POST['status'];
		$db->query("UPDATE tbl_fbsettings SET status = '$status' WHERE fb_id = '$fb_id' AND uid = '$user_id'");
	break;
	
	case 'multipost':
		$user_id = $_SESSION['uid'];
		$multipost_status = $_POST['status'];
		$db->query("UPDATE tbl_users SET multipost = '$multipost_status' WHERE uid = '$user_id'");
	break;

	case 'get_uid':
		echo $_SESSION['uid'];
	break;
	
	case 'logout':
		session_destroy();
	break;
}

?>