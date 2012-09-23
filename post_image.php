<?php
require_once('libs/fb_php_sdk/src/facebook.php');

$facebook = new Facebook(array( 
  'appId'  => '355248497890497',
  'secret' => 'a856b0b1f46f0481785812d0ce55e23f',
));

$return_message = array('error'=>0, 'error_message'=>'');
$message = $_POST['message'];
$filename = $_POST['filename'];


$facebook->setFileUploadSupport(true);
$facebook_setting = $_POST['fb_setting'];

if($facebook_setting == 1){
	$response = $facebook->api(
		'/me/photos/',
		'post',
		array(
			'message' => $message,
			'source' => '@'.realpath($filename) 
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
				"source" => '@'.realpath($filename) 
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
			"source"=>'@'.realpath($filename)
		);
		
		$post_response = $facebook->api("/$page_id/photos", "post", $contents);
	}
}

echo json_encode($return_message);
?>