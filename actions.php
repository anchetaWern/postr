<?php
session_start();
require_once('class.db.php');
require_once('libs/Bcrypt.php');
require_once('class.networks.php');

$db = new db();
$bcrypt = new Bcrypt(15);
$networks = new networks();

$action = $_POST['action'];

switch($action){
	case 'sign_up':

		$uid = 0;
		$email 		= $_POST['email'];
		$password	= $_POST['pword'];
		
		$salt = $bcrypt->getSalt(); 
		$hash = $bcrypt->hash($password, $salt);
		
		$uid = $db->createUser($email, $hash, $salt);
		echo $uid;
		
	break;
	
	case 'login':
		$uid = 0;
		$email 		= $_POST['email'];
		$password	= $_POST['pword'];
		
		$salt = $db->getUserSalt($email);
		$hash = $bcrypt->hash($password, $salt);
		
		$uid = $db->loginUser($email, $hash);
		
		$_SESSION['uid'] = $uid; 
		$_SESSION['email'] = $email;

		//twitter user tokens
		$twitterUserTokens = $db->getTwitterUserTokens($uid);
		if(!empty($twitterUserTokens)){
			$_SESSION['twitteruser_token'] = $twitterUserTokens['oauth_token'];
			$_SESSION['twitteruser_secret'] = $twitterUserTokens['oauth_secret'];
		}

		echo $uid;
		
	break;

	case 'load_settings':

		$user_settings = $db->loadUserSettings();
		echo json_encode($user_settings);
	
	break;

	case 'create_fb_settings':
		$user_id = $_SESSION['uid'];

		$fb_type = $_POST['type'];
		$fb_id	= $_POST['fb_id'];
		$fb_name = $_POST['fb_name'];

		$db->createFbSetting($user_id, $fb_type, $fb_id, $fb_name);

	break;

	case 'update_settings':
		$user_id = $_SESSION['uid'];
		$network = $_POST['network'];
		$network_status = $_POST['status'];

		$db->updateSettings($network_status, $network, $user_id);

	break;

	case 'update_fbsetting':
		$user_id = $_SESSION['uid'];
		$fb_id = $_POST['fb_id'];
		$status = $_POST['status'];
		
		$db->updateFbSetting($status, $fb_id, $user_id);
	break;
	
	case 'multipost':
		$user_id = $_SESSION['uid'];
		$multipost_status = $_POST['status'];
		
		$db->updateMultiStatus($multipost_status, $user_id);
	break;

	case 'post_status':

		$user_id = $_SESSION['uid'];

		$status = $_POST['status'];

		$link = "";
		if(isset($_POST['link'])){
			$link = $_POST['link'];
		}

		$file = "";
		if(isset($_POST['file'])){
			$file = $_POST['file'];
		}
		

		$fbSetting = $db->getNetworkSetting($user_id, 'facebook');
		$fbGroups = $db->getFbGroups($user_id, 'groups');
		$fbPages = $db->getFbGroups($user_id, 'pages');

		$twitterSetting = $db->getNetworkSetting($user_id, 'twitter');

		if($db->hasTwitter($user_id) > 0 && $twitterSetting == 1){
			$networks->tweet(
				$_SESSION['twitteruser_token'], 
				$_SESSION['twitteruser_secret'], 
				$status, 
				$file
			);
		}

		if($fbSetting == 1){
			$networks->postToFbProfile($status, $link, $file);
		}

		$networks->postToFbGroup($fbGroups, $status, $link, $file);
		$networks->postToFbPage($fbPages, $status, $link, $file);

 
		
	break;

	case 'get_uid':
		echo $_SESSION['uid'];
	break;
	
	case 'logout':
		session_destroy();
	break;
}

?>