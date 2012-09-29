<?php
/*Class: Facebook, Twitter & Linkedin Status Update
Author: Md. Mahmud Ahsan (http://thinkdiff.net)
version: 1.0
Date: 24-03-2010
Description: This is an open source php, jquery base application. 
This applications uses the api of facebook, linkedin and twitter to update user's status on those sites. 
This application will be very helpful to learn fbconnect, facebook extended permission and api to update status, 
oAuth for twitter and linkedin authentication. And also to learn linkedin api, twitter api usage.

Copyright (C) 2010  Md. Mahmud Ahsan (mahmud@thinkdiff.net)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
class FbLinkedTwit {
    private $config                         =   array();

    public function __construct(){
        include_once "config.php";
        global $config;
        
        $this->config                       =   $config;
       
    }

    /* This function update facebook user's status */
    /* $id could be facebook page id, if null then session user is used */
    public function facebookStatusUpdate($status='', $id=''){
        include_once $this->config['facebook_library_path'];

        $facebook   =   new Facebook($this->config['fb_api'], $this->config['fb_secret']);
        $user       =   $facebook->api_client->user;

        if (empty($id))
            $id     =   $user;

        if (!empty($id)){
            try{
                $status = $facebook->api_client->users_setStatus($status, $id);
                echo "Facebook status updated successfully!<br />";
            }
            catch(Exception $o){
                echo "<br />Facebook Status couldn't updated!</br>";
                print_r($o);
                echo '<br />';
            }
        }
    }

    /* This function update linkedin user's status */
    public function linkedinStatusUpdate($status='', $requestToken='', $oauthVerifier='', $accessToken=''){
        include_once $this->config['linkedin_library_path'];

        $linkedin = new LinkedIn($this->config['linkedin_access'], $this->config['linkedin_secret']);

        $linkedin->request_token    =   unserialize($requestToken);
        $linkedin->oauth_verifier   =   $oauthVerifier;
        $linkedin->access_token     =   unserialize($accessToken);

        try{
            $stat = $linkedin->setStatus($status);
            echo "Linkedin status updated successfully!<br />";
        }
        catch (Exception $o){
            echo "<br />Linkedin Status couldn't updated!</br>";
            print_r($o);
            echo '<br />';
        }
    }

    public function linkedinGetLoggedinUserInfo( $requestToken='', $oauthVerifier='', $accessToken=''){
        include_once $this->config['linkedin_library_path'];

        $linkedin = new LinkedIn($this->config['linkedin_access'], $this->config['linkedin_secret']);

        $linkedin->request_token    =   unserialize($requestToken); //as data is passed here serialized form
        $linkedin->oauth_verifier   =   $oauthVerifier;
        $linkedin->access_token     =   unserialize($accessToken);

        try{
            $xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,public-profile-url)");
        }
        catch (Exception $o){
            print_r($o);
        }
        return $xml_response;
    }

    /* This function update twitter user's status */
    public function twitterStatusUpdate($status='', $token='', $secret=''){
        include_once $this->config['twitter_library_path'];

        try {
            $to = new TwitterOAuth($this->config['twitter_consumer'], $this->config['twitter_secret'], $token, $secret);

            $params     =   array('status' => $status);
            $do_dm      =   simplexml_load_string($to->OAuthRequest('http://twitter.com/statuses/update.xml', $params, 'POST'));
        
            echo "Twitter status updated successfull!<br />";
        }
        catch(Exception $o) {
            echo "<br />Twitter Status couldn't updated!</br>";
            print_r($o);
            echo '<br />';
        }
    }

    public function twitterGetLoggedinUserInfo($token='', $secret=''){
        include_once $this->config['twitter_library_path'];
        
        $data = '';

        try {
            $to = new TwitterOAuth($this->config['twitter_consumer'], $this->config['twitter_secret'], $token, $secret);
            
            $data     =   simplexml_load_string($to->OAuthRequest('http://api.twitter.com/1/account/verify_credentials.xml', '', 'GET'));
        }
        catch(Exception $o) {
            print_r($o);
        }
        return $data;
    }
}
?>
