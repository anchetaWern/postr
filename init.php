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
$twitterLogin =  $networks->getTwitterLogin();
$twitterLoginText = ' Login';
$twitterUserImg = 'assets/system_img/twitter.png';
$twitterUserName = '';


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

	$twitterLogin = '#';
	$twitterLoginText = '';
}


if(!isset($_SESSION['tumblr_access_token'], $_SESSION['tumblr_access_secret'])){
	if(!isset($_SESSION['tumblr_request_secret'])){
		$tumblrLogin = $networks->getTumblrLogin();
		$tumblrLoginText = " Login";
		$tumblrUserName = "";
		$tumblrPic = "assets/system_img/tumblr.png";
	}

	if(isset($_GET['oauth_token'], $_GET['oauth_verifier'])){
		$networks->unsetTumblrRequest($_GET['oauth_token'], $_GET['oauth_verifier']);
		header('Location: '.$_SERVER['PHP_SELF']);
	}
	
}else{
	$tumblrUser = $networks->getTumblrUserInfo();
	$tumblrLogin = '#';
	$tumblrUserName = $tumblrUser['user_name'];
	$tumblrPic = $tumblrUser['user_avatar'];
}
?>