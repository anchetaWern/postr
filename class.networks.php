<?php
class networks{
	private $config = array();
	private $twitter_auth = array();
	private $twitter_token = array();
	private $twitter_usertoken;
	private $twitter_usersecret;
	private $facebook_ID;
	private $tumblrUser;
	private $tumblrBlog;
	private $tumblrBlogName;
	private $tumblrAuth;

	private $facebook;

	public function __construct(){
		
		require_once('config.php');
		require_once('libs/eden/eden.php');
		require_once('libs/eden/eden/twitter.php');
		require_once('libs/facebook/facebook.php');
		require_once('libs/eden/eden/tumblr.php');
		
		$this->config = $config;

		$this->twitter_auth = eden('twitter')->auth($this->config['TWITTER_KEY'], $this->config['TWITTER_SECRET']);

		$this->facebook = new Facebook(array(
		  'appId'  => $this->config['FB_KEY'],
		  'secret' => $this->config['FB_SECRET'],
		));

		
		if(!empty($_SESSION['fb_access_token'])){
			$this->facebook->setAccessToken($_SESSION['fb_access_token']);
			
		}

		$this->facebook->setFileUploadSupport(true);
		if(!empty($_SESSION['fbuser_id'])){
			$this->facebook_ID = $_SESSION['fbuser_id'];
		}

		if(!empty($_SESSION['tumblr_access_token']) && !empty($_SESSION['tumblr_access_secret'])){
			$this->tumblrUser = eden('tumblr')->user(
				$this->config['TUMBLR_KEY'], $this->config['TUMBLR_SECRET'], 
				$_SESSION['tumblr_access_token'], $_SESSION['tumblr_access_secret']
			);

			$this->tumblrBlog = eden('tumblr')->blog(
				$this->config['TUMBLR_KEY'], $this->config['TUMBLR_SECRET'], 
				$_SESSION['tumblr_access_token'], $_SESSION['tumblr_access_secret']
			);

		}else{
			$this->tumblrAuth = eden('tumblr')->auth(
				$this->config['TUMBLR_KEY'], $this->config['TUMBLR_SECRET']
			);
		}


	}

	public function getfbid(){
		return $this->facebook_ID;
	}

	public function getAccess(){
		return $this->config['FB_TOKEN'];
	}

	public function getConfig($key){
		return $this->config[$key];
	}

	public function setTwitterRequestToken(){
		$this->twitter_token = $this->twitter_auth->getRequestToken();
		$_SESSION['request_secret'] = $this->twitter_token['oauth_token_secret'];
	}

	public function getTwitterLogin(){
		$login = $this->twitter_auth->getLoginUrl($this->twitter_token['oauth_token'], 'http://goo.gl');
		return $login;
	}

	public function setTwitterUserAccessToken($oauth_token, $oauth_verifier){
		$token = $this->twitter_auth->getAccessToken(
				$oauth_token, 
				$_SESSION['request_secret'], 
				$oauth_verifier
		);

		$this->twitter_usertoken = $token['oauth_token'];
		$this->twitter_usersecret = $token['oauth_token_secret'];
		$this->storeUserToken();
	}

	public function storeUserToken(){
		$_SESSION['twitteruser_token'] = $this->twitter_usertoken;
		$_SESSION['twitteruser_secret'] = $this->twitter_usersecret; 
	}

	public function getTwitterUserInfo(){
			$user = array();
			$users = eden('twitter')->users(
				$this->config['TWITTER_KEY'], 
				$this->config['TWITTER_SECRET'], 
				$_SESSION['twitteruser_token'], 
				$_SESSION['twitteruser_secret']
			);
			
			$user_info = $users->getCredentials();
			
			$username = $user_info['screen_name'];
			$oauth_id = $user_info['id'];
			$user_img = $user_info['profile_image_url'];

			$user = array('username' => $username, 'oauth_id' => $oauth_id, 'user_img' => $user_img);
			return $user;
	}

	public function tweet($user_token, $user_secret, $status, $file = ''){
		$tweets = eden('twitter')->tweets(
			$this->config['TWITTER_KEY'], $this->config['TWITTER_SECRET'], 
			$user_token, $user_secret
		);	

		if($file == ''){//status only
			$tweets->tweet($status);

		}else{//status with image
			$tweets->tweetMedia($status, '@'.realpath($file));
		}
	}

	public function hasFbUser(){

		return $this->facebook->getUser();
	}

	public function boom(){
		$voom = "";
		try {
   	 $me = $this->facebook->api('/me');
    if ($me) {
      //User is logged in
      $voom =  "yep";
    }
	  } catch (FacebookApiException $e) {
	    //User is not logged in
	    $voom = "nopes";
	  }
	  return $voom;
	}

	public function getFbLoginUrl(){
		$scope = "user_about_me,email,read_friendlists,publish_stream,manage_pages,user_groups,user_photos";
		$redirect_url = "http://127.0.0.1:8020/postr/postr.php";
		$options = array("scope" => $scope, "redirect_uri" => $redirect_url);
		return $this->facebook->getLoginUrl($options);
	}

	public function getFbUser($fbUserID){

		$fbUser = $this->facebook->api(
			array(
				"method" => "fql.query", 
				"query" => "SELECT name, pic_small FROM user WHERE uid=" . $fbUserID
			)
		);

		return $fbUser;
	}

	public function getFbLogoutUrl(){

		return $this->facebook->getLogoutUrl();
	}

	public function postToFbProfile($status, $lists, $link = '', $file = ''){
		
		$postContents = $this->getFbStatus($status, $link, $file);

		if($lists != ''){
			$postContents["privacy"] = array("value" => "CUSTOM", "friends" => "SOME_FRIENDS", "allow" => $lists);
			
		}

		if($file != ''){
			$this->facebook->api('/'. $this->facebook_ID .'/photos', 'post', $postContents);
		}else{
			$this->facebook->api('/'. $this->facebook_ID .'/feed', 'post', $postContents);
		}
		
	}

	public function postToFbGroup($groups, $status, $link = '', $file = ''){
		
		$postContents = $this->getFbStatus($status, $link, $file);

		foreach($groups as $group){
			$group_id = $group['fb_id'];
			$isAGroupMember = $this->FBGroupMemberCount($group_id);
	

			if($isAGroupMember){
				if($file != ''){
					$response = $this->facebook->api("/$group_id/photos", "post", $postContents);
				}else{
					$response = $this->facebook->api("/$group_id/feed", "post", $postContents);
				}
				
					
			}
		}
	}



	public function postToFbPage($pages, $status, $link = '', $file = ''){

		
		
		$postContents = $this->getFbStatus($status, $link, $file);

		foreach($pages as $page){

			$page_id = $page['fb_id'];
			$isAPageFan = $this->FBPageFanCount($page_id);
			$isAPageAdmin = $this->FBPageAdminCount($page_id);

			if($isAPageAdmin || $isAPageFan){
				$page_data = $this->facebook->api("/$page_id", array("fields" => "access_token"));
				$page_access_token = $page_data['access_token'];
				
				$postContents['access_token'] = $page_access_token;

				if($file != ''){
					$post_response = $this->facebook->api("/$page_id/photos", "post", $postContents);
				}else{
					$post_response = $this->facebook->api("/$page_id/feed", "post", $postContents);
				}
				
			}
		}
	}

	public function FBGroupMemberCount($fbGroupID){
		
		$groupMember = $this->facebook->api(array(
			"method" => "fql.query",
			"query" => "SELECT gid, uid FROM group_member WHERE gid = '$fbGroupID' AND uid = '$this->facebook_ID'"
		));
		return count($groupMember);
	}

	public function FBPageFanCount($fbPageID){

		$pageLiked = $this->facebook->api(array(
			"method" => "fql.query",
			"query" => "SELECT page_id FROM page_fan WHERE uid = '$this->facebook_ID'  AND page_id = '$fbPageID'"
		));
		return count($pageLiked);
	}

	public function FBPageAdminCount($fbPageID){

		$pageAdmin = $this->facebook->api(array(
			"method" => "fql.query",
			"query" => "SELECT page_id FROM page_admin WHERE uid = '$this->facebook_ID'  AND page_id = '$fbPageID'"
		));
		return count($pageAdmin);
	}

	public function FBFriendListCount($fbListID){

		$friendlist = $this->facebook->api(array(
			"method" => "fql.query",
			"query" => "SELECT flid FROM friendlist WHERE owner = '$this->facebook_ID'  AND flid = '$fbListID'"
		));
		return count($friendlist);
	}

	public function getFbStatus($status, $link = '', $file = ''){
		
		$statusContents = array();
		if($link != '' && $file != ''){
			
			$statusContents = array('message' => $status, 'link' => $link, 'source' => '@'.realpath($file));
		}else if($link != '' && $file == ''){
			
			$statusContents = array('message' => $status, 'link' => $link);
		}else if($link == '' && $file != ''){

			$statusContents = array('message' => $status, 'source' => '@'.realpath($file));
		}else{
			$statusContents = array('message' => $status);
		}

		return $statusContents;
	}

	public function setTumblrBlog($blogName, $blogTitle){
		$this->tumblrBlogName = $blogName;
		$this->tumblrBlogTitle = $blogTitle;
	}

	public function getTumblrLogin(){

	  $token = $this->tumblrAuth->getRequestToken();
	  $_SESSION['tumblr_request_secret'] = $token['oauth_token_secret'];
	  $login = $this->tumblrAuth->getLoginUrl($token['oauth_token'], $this->config['TUMBLR_LOGIN']);
       	
    return $login;
	}

	public function unsetTumblrRequest($oauth_token = '', $oauth_verifier = ''){
		
    $token = $this->tumblrAuth->debug(true)->getAccessToken($oauth_token, $_SESSION['tumblr_request_secret'], $oauth_verifier);
    
    $_SESSION['tumblr_access_token']   = $token['oauth_token'];
    $_SESSION['tumblr_access_secret']  = $token['oauth_token_secret'];
   
    unset($_SESSION['tumblr_request_secret']);
	}

	public function getTumblrUserInfo(){
		
		$userData = $this->tumblrUser->getInfo();

    $blogName = substr($userData['response']['user']['blogs'][0]['url'], 7, -1);
    $blogTitle = $userData['response']['user']['blogs'][0]['title']; 
    $this->setTumblrBlog($blogName, $blogTitle);

		$userAvatar = $this->tumblrBlog->setSize(50)->getAvatar($blogName);

   	$userInfo = array(
    	"user_name" => $userData['response']['user']['name'],
    	"user_avatar" => $userAvatar['response']['avatar_url'],
    	"blog_name" => $blogName,
    	"blog_title" => $blogTitle
    );

    return $userInfo;
	}


	public function postTumblrText($title, $body){
		$response = $this->tumblrBlog->postText($this->tumblrBlogName, $title, $body);
		return $response;
	}

	public function postTumblrQuote($quote){
		$response = $this->tumblrBlog->postQuote($this->tumblrBlogName, $quote);
		return $response;
	}

	public function postTumblrPhoto($image){
		$response = $this->tumblrBlog->postPhoto($this->tumblrBlogName, $image);
		return $response;
	}

	public function postTumblrVideo($video){
		$response = $this->tumblrBlog->postVideo($this->tumblrBlogName, $video);
		return $response;
	}

}
?>