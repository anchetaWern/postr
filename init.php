<?php
require_once('class.networks.php');
require_once('class.db.php');

$networks = new networks();
$db = new db();

$user_id = $_SESSION['uid'];

if($db->hasTwitter($user_id) == 0){ //new user
	if(!isset($_SESSION['twitteruser_token'], $_SESSION['twitteruser_secret'])){
	  
		if(!isset($_SESSION['request_secret'])){

		  $networks->setTwitterRequestToken();
			$twitterUrl =  $networks->getTwitterLogin();
			$twitterUrlText = ' Login';
			$twitterUserImg = 'img/default.png';
		}
	   
		if(isset($_GET['oauth_token'], $_GET['oauth_verifier'])){
			
			$networks->setTwitterUserAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);
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