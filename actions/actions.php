<?php
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
		$email 		= $_POST['email'];
		$password	= $_POST['pword'];
	break;
}

?>