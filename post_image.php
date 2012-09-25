<?php
require_once('libs/fb_php_sdk/src/facebook.php');
require_once('libs/eden/eden.php');
require_once('libs/eden/eden/twitter.php');

/*CONFIGS*/
$facebook = new Facebook(array( 
  'appId'  => '355248497890497',
  'secret' => 'a856b0b1f46f0481785812d0ce55e23f',
));

define('TWITTER_TOKEN', 'RATMGupqLicAGXCnaGtcA');
define('TWITTER_SECRET', 'yNCmLJla7UJ8IcAGviH4RZAXxl2jOfHFzXFKvBTYik');

$user_token = $_SESSION['access_token'];
$user_secret = $_SESSION['access_secret'];

$tweets = eden('twitter')->tweets(TWITTER_TOKEN, TWITTER_SECRET, $user_token, $user_secret);


/*FACEBOOK*/
$return_message = array('error'=>0, 'error_message'=>'');
$message = $_POST['message'];

$has_file = 1;
if(!empty($_POST['filename'])){
	$file = realpath($_POST['filename']);
}else{
	$has_file = 2;
}

$facebook->setFileUploadSupport(true);
$facebook_setting = $_POST['fb_setting'];

if($facebook_setting == 1){
	$response = $facebook->api(
		'/me/photos/',
		'post',
		array(
			'message' => $message,
			'source' => '@'.$file
		)
	);
}

$groups = $_POST['fb_groups'];
foreach($groups as $group_id=>$group){
	$group_status = $group['group_status'];
	
	if($group_status == 1){
		$response = $facebook->api(
			"/$group_id/photos/",
			"post",
			array(
				"message" => $message,
				"source" => '@'.$file
			)
		);
	}
}

$pages = $_POST['fb_pages'];
foreach($pages as $page_id=>$page){
	if($page['page_status'] == 1){
		$page_data = $facebook->api("/$page_id", array("fields" => "access_token"));
		$page_access_token = $page_data['access_token'];
		
		$contents = array(
			"access_token"=>$page_access_token, 
			"message"=>$message, 
			"source"=>'@'.$file
		);
		
		$post_response = $facebook->api("/$page_id/photos", "post", $contents);
	}
}

/*TWITTER*/
$twitter_setting = $_POST['twitter_setting'];

if($twitter_setting == 1){
	if($has_file == 1){//with media
		$tweets->tweetMedia($message, '@'.$file);
	}else{//plain tweet
		$tweets->tweet($message);
	}
}

echo json_encode($return_message);
?>