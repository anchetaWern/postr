<?php
require_once('libs/fb_php_sdk/src/facebook.php');

$facebook = new Facebook(array( 
  'appId'  => '355248497890497',
  'secret' => 'a856b0b1f46f0481785812d0ce55e23f',
));

$response = array('error'=>0, 'error_message'=>'');
$message = $_POST['message'];
$filename = $_POST['filename'];

try{
	$facebook->setFileUploadSupport(true);
	$response = $facebook->api(
		'/me/photos/',
		'post',
		array(
			'message' => $message,
			'source' => '@'.realpath($filename) 
		)
	);
}catch(FacebookApiException $e){
	$response['error'] = 1;
	$response['error_message'] = $e;
	$response['filename'] = $filename;
}

echo json_encode($response);
?>