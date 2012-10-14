<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require_once('class.networks.php');
require_once('class.db.php');
require_once('class.user.php');

$networks = new networks();
$db = new db();
$user = new user();

$userSession = $user->checkUserSession();
$userCookie = $user->checkUserCookie();

if($userCookie){
	$_SESSION['uid'] = $_COOKIE['user'];
}

$user_id = $_SESSION['uid'];
$userInfo = $db->getUserInfo($user_id);

//twitter defaults
$networks->setTwitterRequestToken();
$twitterUrl =  $networks->getTwitterLogin();
$twitterUrlText = ' Login';
$twitterUserImg = 'img/default.png';
$twitterUserName = '';

//facebook defaults
$fbUser = "";
$fbUrlText = "";
$fbUserImg = "img/default.png";

$oAuthData = $db->getOauth($user_id, "facebook");
if(!empty($oAuthData) && $networks->hasFbUser() == $oAuthData['oauth_id']){

	$fbUserID = $oAuthData['oauth_id'];
	$fbOauthdata = $networks->getFbUser($fbUserID);
	$_SESSION['fbuser_id'] = $fbUserID;
	$_SESSION['fbuser_name'] = $fbOauthdata[0]['name'];

	$fbUser = $fbOauthdata[0]['name'];
	$fbUserImg = $fbOauthdata[0]['pic_small'];

	$_SESSION['fblogin_status'] = "verified_user";

}else if($networks->hasFbUser()){

	$fbUserID = $oAuthData['oauth_id'];
	$fbUser = "Unknown user <a href='#' id='facebook_logout'>Logout</a>";

	$_SESSION['fblogin_status'] = "unknown_user";
}else{
	$fbUrlText = " Login";
	$_SESSION['fblogin_status'] = "no_user";
}



if($db->hasOauth($user_id, "twitter") == 0){ //new user
	if(!isset($_SESSION['twitteruser_token'], $_SESSION['twitteruser_secret'])){
	  
	
		if(isset($_GET['oauth_token'], $_GET['oauth_verifier'])){
			
			$networks->setTwitterUserAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);
			$oauthUser = $networks->getTwitterUserInfo();

			$oauthID = $oauthUser['oauth_id'];
			$oauthUsername = $oauthUser['username'];
			$oauth_token = $_SESSION['twitteruser_token'];
			$oauth_secret = $_SESSION['twitteruser_secret'];

			$db->createOauth($user_id, $oauthID, 'twitter', $oauth_token, $oauth_secret, $oauthUsername);

			unset($_SESSION['request_secret']);

			header('Location: '.$_SERVER['PHP_SELF']);	
		}
	}
}else{ //existing user
	$twitterUser = $db->getTwitterUserTokens($user_id);

	$user->setTwitterUserTokens($twitterUser['oauth_token'], $twitterUser['oauth_secret']);

	$twitterUser = $networks->getTwitterUserInfo();
	$twitterUserName = $twitterUser['username'];
	$twitterUserImg  = $twitterUser['user_img'];

	$twitterUrl = '#';
	$twitterUrlText = '';
}	
?>