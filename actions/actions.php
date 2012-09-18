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
			
			$get_networks = $db->query("SELECT sid FROM tbl_settings");
			if($get_networks->num_rows > 0){
				
				while($row = $get_networks->fetch_object()){
					$sid = $row->sid;
					$db->query("INSERT INTO tbl_usersettings SET uid = '$uid', sid = '$sid'");
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
	
	case 'set':
		$uid = $_SESSION['uid'];
		$sid = $_POST['sid'];
		$status = $_POST['status'];
		
		$db->query("UPDATE tbl_usersettings SET status = '$status' WHERE uid = '$uid' AND sid = '$sid'");
	break;
	
	case 'load_set':
		$uid = $_SESSION['uid'];
		$settings = [];
		$get_settings = $db->query("SELECT sid, status FROM tbl_usersettings WHERE uid = '$uid'");
		if($get_settings->num_rows > 0){
			while($row = $get_settings->fetch_object()){
				$sid = $row->sid;
				$status = $row->status;
				$settings[] = ['sid'=>$sid, 'status'=>$status];
			}
		}
		echo json_encode($settings);
	break;
	
	case 'logout':
		session_destroy();
	break;
}

?>