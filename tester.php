<?php
session_start();
//session_destroy();
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


$attachment = array(
	'message' => '...',
	'name' => ',,',
	'description' => '.',
	'link' => 'http://anchetawern.github.com'
);
require_once('class.networks.php');
$networks = new networks();
print_r($networks->fba($attachment));
 */

$config['FB_KEY'] = '355248497890497';
$config['FB_SECRET'] = 'a856b0b1f46f0481785812d0ce55e23f';
$config['FB_TOKEN'] = '355248497890497|Ro5RxJFHlzwRliAhbKiZuUJf9-M';

require_once('libs/facebook/facebook.php');
$fb = new Facebook(array(
		  'appId'  => $config['FB_KEY'] ,
		  'secret' => $config['FB_SECRET']
		));

 $result = $fb->getUser();
 echo $result;
if($result){
	//$fb->setAccessToken("355248497890497|Ro5RxJFHlzwRliAhbKiZuUJf9-M");
	$user = $fb->api('/me');
	
	print_r($user);
}

echo $_SESSION['fb_access_token'];
    


//$data = $fb->getUser();			  	
//print_r($data);
//$fb->setAccessToken("AAAFDGLyGFMEBAIFeJbZA5Bm1KCnQQO775Ub3P98rhptcpJsGsW81Ypxemhhpk4AgMY7U1VPW93BQ13VZBZA5U//dcK9jA4rlxqmTnIQ3v4CG2oVD8ZCJXM");


//echo $fb->getExtendedAccessToken();
/*
$data = $fb->api(
	array(
		"method" => "fql.query", 
		"query" => "SELECT gid, uid FROM group_member WHERE gid = 147243238636683 AND uid = 1659824789")
);

print_r($data);

/*
$attachment = array(
	'message' => '...',
	'name' => ',,',
	'description' => '.',
	'link' => 'http://anchetawern.github.com'
);



$data =$fb->api(
	array(
"method" => "fql.multiquery",
	"queries" => array(
		"q1" => "SELECT page_id FROM page_admin WHERE uid = 1659824789",
		"q2" => "SELECT page_id, name, pic_small, description FROM page WHERE page_id IN (SELECT page_id FROM #q1)"
	)
	)
);

require_once('class.networks.php');
$networks = new networks();
$data = $networks->FBGroupMemberCount("147243238636683");

print_r($data);

//$response = $fb->api('/1659824789/feed', 'post', $attachment);
//print_r($response);

/*
require_once('class.networks.php');
$networks = new networks();
require_once('class.db.php');
$db = new db();
$d =$db->getOauth($_SESSION['uid'], "facebook");

$fbUserID = $d['oauth_id'];
$fbOauthdata = $networks->getFbUser($fbUserID);
print_r($fbOauthdata);
echo $fbOauthdata[0]['pic_small'];
echo '<br/>';
echo $_SESSION['fbuser_id'];
echo '<br/>';
echo 'FACEBOOK ID: '.$networks->getFBID();
echo '<hr/>';
//$networks->postToFbProfile("post using app access token only", "", "http://anchetawern.github.com", '');
 */
?>