<?php
session_start();
require_once('class.networks.php');
require_once('class.db.php');

$networks = new networks();
$db = new db();
$user_id = $_SESSION['uid'];

foreach($_POST['status'] as $row){
	echo trim($row)."\n";
}
/*
$fbSetting = $db->getNetworkSetting($user_id, 'facebook');
		$fbGroups = $db->getFbGroups($user_id, 'groups');
		$fbPages = $db->getFbGroups($user_id, 'pages');

		$d = $networks->getFbStatus($_POST['status'], $_POST['link'], $_POST['file']);

		print_r($d);
		//$networks->postToFbProfile(']', '');
/*
		$twitterSetting = $db->getNetworkSetting($user_id, 'twitter');
		$fbSetting = $db->getNetworkSetting($user_id, 'facebook');
		echo $fbSetting;
/*
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
/*
if(empty($_SESSION['twitteruser_token']) && empty($_GET['oauth_token'])){

	$networks->setTwitterRequestToken();
	header('Location:'.$networks->getTwitterLogin());

}else if(isset($_GET['oauth_token'], $_GET['oauth_verifier'])){
	$networks->setTwitterUserAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);
	header('Location:tester.php');
}else{
	$networks->getTwitterUserInfo();
	$networks->tweet($_SESSION['twitteruser_token'], $_SESSION['twitteruser_secret'], 'from class');
}
 */


?>