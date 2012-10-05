<?php
session_start();
require_once('class.db.php');
require_once('libs/Bcrypt.php');
require_once('class.networks.php');
require_once('libs/class.googl.php');


$db = new db();
$bcrypt = new Bcrypt(15);
$networks = new networks();
$googl = new Googl();

$action = $_POST['action'];

switch($action){
	case 'sign_up':
		session_destroy();
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
		$remember = $_POST['remember'];

		$expireTime = time() + 60 * 60 * 24 * 15; //15 days
		$deleteTime = time () - 1;

		$salt = $db->getUserSalt($email);
		$hash = $bcrypt->hash($password, $salt);
		
		$uid = $db->loginUser($email, $hash);

		if($uid != 0){
			$_SESSION['uid'] = $uid; 
			$_SESSION['email'] = $email;

			if($remember != 'off'){
				setcookie("user", "", $deleteTime, '/'); //delete previous user
				setcookie("user", $uid, $expireTime, '/'); //set new user
			}
		}
		

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
		$img_url = $_POST['img_url'];

		$db->createFbSetting($user_id, $fb_type, $fb_id, $fb_name, $img_url);

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
		$fbLoginStatus = $_POST['fb_login_status'];

		$longUrls = '';
		if(isset($_POST['long_urls'])){
			$longUrls = $_POST['long_urls'];
		}
		
		$shortUrls = getShortUrls($longUrls);
		$status = replaceLongUrls($status, $longUrls, $shortUrls);

		$link = "";
		if(!empty($shortUrls)){
			$link = $shortUrls[0];
		}
		
		$file = "";
		if(isset($_POST['file'])){
			$file = $_POST['file'];
		}

		if(is_array($status)){ //multipost (only links)
			foreach($status as $post){
				$post = trim($post);
				if(filter_var($post, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE)){
					$post = $googl->createShortcut($post);
					
				}
				postToNetworks($user_id, $post, $fbLoginStatus, $post);
			}
			
		}else{ //single post (can contain text with one or more links)
			
			postToNetworks($user_id, $status, $fbLoginStatus, $link, $file);
		}	


	break;

	case 'get_uid':

		echo $_SESSION['uid'];
	break;
	
	case 'logout':

		session_destroy();
		$deleteTime = time () - 1;
		setcookie("user", "", $deleteTime, '/');
		
	break;
}

function postToNetworks($user_id, $status, $fbloginstatus, $link = '', $file = ''){
	global $db;
	global $networks;

	$fbSetting = $db->getNetworkSetting($user_id, 'facebook');
	$fbGroups = $db->getFbGroups($user_id, 'groups');
	$fbPages = $db->getFbGroups($user_id, 'pages');
	$fbLists = $db->getFbGroups($user_id, 'lists');

	$twitterSetting = $db->getNetworkSetting($user_id, 'twitter');

	if($db->hasTwitter($user_id) > 0 && $twitterSetting == 1){
		$res = $networks->tweet(
			$_SESSION['twitteruser_token'], 
			$_SESSION['twitteruser_secret'], 
			$status, 
			$file
		);
	}

	if($fbloginstatus == 'connected'){
		if($fbSetting == 1){

			$friendList = array();
			foreach($fbLists as $row){
				array_push($friendList, $row['fb_id']);
			}

			$listIDs = implode(",", $friendList);

			$networks->postToFbProfile($status, $listIDs, $link, $file);
		}

		$networks->postToFbGroup($fbGroups, $status, $link, $file);
		$networks->postToFbPage($fbPages, $status, $link, $file);
		$networks->postToFbGroup($fbLists, $status, $link, $file); //list(same structure with groups)
	}

}

function replaceLongUrls($status, $long_urls, $short_urls){
	
	if(!empty($long_urls)){
		$status =  str_replace($long_urls, $short_urls, $status);
	}
	return $status;
}

function getShortUrls($long_urls){
	global $googl;
	$shortUrls = array();

	if(is_array($long_urls)){

		foreach($long_urls as $long_url){
			if(filter_var($long_url, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE)){
				$short_url = $googl->createShortcut($long_url);
				array_push($shortUrls, $short_url);
			}
		}

	}else{
		if(filter_var($long_urls, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE)){
			$shortUrls = $googl->createShortcut($long_urls);
		}
	}
	return $shortUrls;
}
?>