<?php
require_once('class.networks.php');
require_once('class.db.php');

$networks = new networks();
$db = new db();

$user_id = $_SESSION['uid'];

$networks->setTwitterRequestToken();
$twitterUrl =  $networks->getTwitterLogin();
$twitterUrlText = ' Login';
$twitterUserImg = 'img/default.png';
$twitterUserName = '';

if($db->hasTwitter($user_id) == 0){ //new user
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
	
	$_SESSION['twitteruser_token'] 		= $twitterUser['oauth_token'];
	$_SESSION['twitteruser_secret'] 	= $twitterUser['oauth_secret'];

	$twitterUser = $networks->getTwitterUserInfo();
	$twitterUserName = $twitterUser['username'];
	$twitterUserImg  = $twitterUser['user_img'];

	$twitterUrl = '#';
	$twitterUrlText = '';
}	

$fbUrl = "#";
$fbUrlText = "";
if($networks->hasFbUser() > 0){ //has a current fb user
	$fbUrlText = "";
	$fbUrl = $networks->getFbLogoutUrl();
}else{
	$fbUrlText = " Login";
	$fbUrl = $networks->getFbLoginUrl();
}
?>