<?php
session_start();
/*
require_once('class.db.php');
$db = new db();
$oauthData = $db->getOauth($_SESSION['uid'], 'facebook'); 
$access_token = $oauthData['oauth_token'];
echo $access_token;
echo "<br/>";

$access_token = "AAAFDGLyGFMEBADBddni2XgfPjh6NyPNoSixf7wnrcEYFZAXKrRWR2pZAx7f0jqrYmj3NCZCzS1gKrxlWJXvwR9R0ZClehEgICfYTGSvS58QqYC7blIPn";
//check if access token has expired or not
$graph_url = "https://graph.facebook.com/me?access_token=" . $access_token;
$response = curl_get_file_contents($graph_url);
echo $response;
$decoded_response = json_decode($response);


	function curl_get_file_contents($url){
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $url);
    $contents = curl_exec($c);

    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents){
    	return $contents;
    }else{
    	return false;
    } 
  }


$config['FB_KEY'] = '355248497890497';
$config['FB_SECRET'] = 'a856b0b1f46f0481785812d0ce55e23f';

require_once('libs/facebook/facebook.php');
$fb = new Facebook(array(
		  'appId'  => $config['FB_KEY'] ,
		  'secret' => $config['FB_SECRET']
		));

$attachment = array(
	'message' => '...',
	'name' => ',,',
	'description' => '.',
	'link' => 'http://anchetawern.github.com'
);

$fb->setAccessToken($_SESSION['fbAccessToken']);
$response = $fb->api('/me/feed', 'post', $attachment);
print_r($response);
//$fb->setAccessToken("AAAFDGLyGFMEBADBddni2XgfPjh6NyPNoSixf7wnrcEYFZAXKrRWR2pZAx7f0jqrYmj3NCZCzS1gKrxlWJXvwR9//R0ZClehEgICfYTGSvS58QqYC7blIPn");


/*
$user = $fb->api("/me");
print_r($user);

$fbAccessToken = $networks->getFBAcessToken("AAAFDGLyGFMEBACYTstLQGFrg78c47ACD2c1B5ttkpedYsZB2kjDa7Nilb7GWDgLFVKmXcrck8P9T0ZCdU9KSWDcbYVKyApnT46pvFV4KMZBwG10cKpT");
echo $fbAccessToken;
 */

$attachment = array(
	'message' => '...',
	'name' => ',,',
	'description' => '.',
	'link' => 'http://anchetawern.github.com'
);
require_once('class.networks.php');
$networks = new networks();
print_r($networks->fba($attachment));
?>