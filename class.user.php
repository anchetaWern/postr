<?php
class user{
	
	public function checkUserCookie(){
		$hasCookie = false;
		if(!empty($_COOKIE['user'])){
			$hasCookie = true;
		}

		return $hasCookie;
	}

	public function checkUserSession(){
		$hasSession = false;
		if(!empty($_SESSION['uid'])){
			$hasSession = true;
		}

		return $hasSession;
	}

	public function setTwitterUserTokens($oauthToken, $oauthSecret){
		$_SESSION['twitteruser_token'] 		= $oauthToken;
		$_SESSION['twitteruser_secret'] 	= $oauthSecret;
	}

	public function setTumblrUserTokens($oauthToken, $oauthSecret){
		$_SESSION['tumblr_access_token'] = $oauthToken;
		$_SESSION['tumblr_access_secret'] = $oauthSecret;
	}

}
?>