<?php
require_once('actions/conn.php');
require_once('libs/fb_php_sdk/src/facebook.php');
require_once('libs/eden/eden.php');
require_once('libs/eden/eden/twitter.php');
require_once('libs/linkedin/linkedin.php');
require_once('includes/keys.php');

/*CONFIGS*/
$facebook = new Facebook(array( 
  'appId'  => FB_APPID,
  'secret' => FB_SECRET,
));

$user_token = $_SESSION['access_token'];
$user_secret = $_SESSION['access_secret'];



$linkedin_tokens = array(
	'consumerKey' => LINKEDIN_KEY,
	'consumerSecret' => LINKEDIN_SECRET
);


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

if(!empty($file)){
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
}

$groups = isset($_POST['fb_groups']) or $groups = "";

if(!empty($file) && $groups != ''){
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
}

$pages = isset($_POST['fb_pages']) or $pages = "";
if(!empty($file) && $pages != ''){
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
}

/*TWITTER*/
$user_id = $_SESSION['uid'];
$has_twitter = $db->query("SELECT user_id FROM tbl_oauth WHERE user_id = '$user_id' AND provider = 'twitter'");
if($has_twitter->num_rows > 0){

	$twitter_setting = $_POST['twitter_setting'];
	if($twitter_setting == 1){
		$tweets = eden('twitter')->tweets(TWITTER_TOKEN, TWITTER_SECRET, $user_token, $user_secret);	
		if($has_file == 1){//with media
			$tweets->tweetMedia($message, '@'.$file);
		}else{//plain tweet
			$tweets->tweet($message);
		}
	}
}



/*LINKED IN*/
$linkedin_setting = $_POST['linkedin_setting'];

if($linkedin_setting == 1){

	$linkedin = new linkedIn($linkedin_tokens);
	$linkedin->connect();
	$linkedin->updateStatus($message);
}


echo json_encode($return_message);
?>