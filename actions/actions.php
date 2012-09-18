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
			
			$rows = $db->affected_rows;
			echo $rows;
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
	
	case 'logout':
		session_destroy();
	break;
}

?>