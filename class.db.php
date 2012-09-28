<?php
class db{
	private $conn;

	public function __construct(){
		require_once('actions/conn.php');
		$this->conn =  $db;
	}

	public function createUser($email, $hash, $salt){
		$uid = 0;
		if($query = $this->conn->prepare("INSERT INTO tbl_users SET email = ?, hashed_password = ?, salt = ?")){
			$query->bind_param("sss", $email, $hash, $salt);
			$query->execute();
			$uid = $query->insert_id;
			
			//create default settings for new user
			$select_networks = $this->conn->query("SELECT network FROM tbl_networks");
			if($select_networks->num_rows > 0){
				while($row = $select_networks->fetch_object()){
	
					$network = $row->network;
					$this->conn->query("INSERT INTO tbl_settings SET network = '$network', uid = '$uid', status = 0");
				}
			}

			
		}
		return $uid;
	}

	public function getUserSalt($email){

		$salt = '';
		$select_salt = $this->conn->query("SELECT salt FROM tbl_users WHERE email = '$email'");

		if($select_salt->num_rows > 0){
			$row = $select_salt->fetch_object();
			$salt = $row->salt;
		}

		return $salt;
	}

	public function loginUser($email, $hash){
		$uid = 0;
		$select_salt = $this->conn->query("SELECT salt FROM tbl_users WHERE email = '$email'");
			
		$select_user = $this->conn->query("
			SELECT uid FROM tbl_users WHERE email = '$email' 
			AND hashed_password = '$hash'
		");

		if($select_user->num_rows > 0){
			$row = $select_user->fetch_object();
			$uid = $row->uid;
		}
	
		return $uid;
	}

	public function loadUserSettings(){
		
		$user_settings = array();
		$users = $this->conn->query("SELECT uid, multipost FROM tbl_users");
		if($users->num_rows > 0){
			while($user_row = $users->fetch_object()){

					$uid = $user_row->uid;
					$multipost_status = $user_row->multipost;

					//load general settings
					$settings = $this->conn->query("SELECT network, status FROM tbl_settings WHERE uid = '$uid'");
					$fb_status = 0;

					if($settings->num_rows > 0){
						while($row = $settings->fetch_object()){

							$network = $row->network;
							$status = $row->status;
							$user_settings[$uid]['settings'][$network] = array('status' => $status);
							if($network == 'facebook'){
								$fb_status = $status;
							}
						}
					}//end general settings if

					//facebook settings
					$fb_settings = $this->conn->query("SELECT fb_type, fb_id, fb_name, status FROM tbl_fbsettings WHERE uid = '$uid'");
					$fb_settings_data = array();
					if($fb_settings->num_rows > 0){
						while($row = $fb_settings->fetch_object()){
							$fb_type = $row->fb_type;
							$fb_id = $row->fb_id;
							$fb_name = $row->fb_name;
							$status =  $row->status;

							$prefix = substr($fb_type, 0, -1) . '_';
							
							$fb_settings_data[$fb_type][$fb_id] = array($prefix."name" => $fb_name, $prefix."status" => $status);
							
						}
					}//end facebook settings if

					$user_settings[$uid]['settings']['facebook'] = $fb_settings_data;
					$user_settings[$uid]['settings']['facebook']['status'] = $fb_status;
					$user_settings[$uid]['settings']['multipost'] = $multipost_status;

			}//end load users while
		}//end load users if

		return $user_settings;
	}

	public function createFbSetting($user_id, $fb_type, $fb_id, $fb_name){

		$this->conn->query("
			INSERT INTO tbl_fbsettings SET uid = '$user_id', 
			fb_type = '$fb_type', fb_id = '$fb_id', fb_name = '$fb_name'
		");

		return $this->conn->affected_rows;
	}

	public function updateFbSetting($status, $fb_id, $user_id){

		$this->conn->query("
			UPDATE tbl_fbsettings SET status = '$status' WHERE fb_id = '$fb_id' AND uid = '$user_id'
		");

		return $this->conn->affected_rows;
	}

	public function updateSettings($network_status, $network, $uid){

		$this->conn->query("
			UPDATE tbl_settings SET status = '$network_status'
			WHERE network = '$network' AND uid = '$uid'
		");

		return $this->conn->affected_rows;
	}

	public function updateMultiStatus($multipost_status, $user_id){

		$this->conn->query("
			UPDATE tbl_users SET multipost = '$multipost_status' WHERE uid = '$user_id'
		");

	}

	public function hasTwitter($user_id){

		$has_twitter = $this->conn->query("
			SELECT user_id FROM tbl_oauth WHERE user_id = '$user_id' AND provider = 'twitter'
		");
		return $has_twitter->num_rows;
	
	}

	public function getTwitterUserTokens($user_id){

		$tokens = array();
		$selectUserTokens = $this->conn->query("
			SELECT oauth_token, oauth_secret 
			FROM tbl_oauth WHERE user_id = '$user_id'
		");

		if($selectUserTokens->num_rows > 0){
			
			$userTokens = $selectUserTokens->fetch_object();
			$tokens['oauth_token'] = $userTokens->oauth_token;
			$tokens['oauth_secret'] = $userTokens->oauth_secret;
		}
		
		return $tokens;
	}

	public function getFbGroups($user_id, $group_type){
		$groups = array();
		$selectGroups = $this->conn->query("
			SELECT fb_id, fb_name, status FROM tbl_fbsettings WHERE uid = '$user_id' 
			AND fb_type = '$group_type'
		");

		if($selectGroups->num_rows > 0){
			while($group = $selectGroups->fetch_object()){

				$fb_id = $group->fb_id;
				$fb_name = $group->fb_name;
				$status = $group->status;

				$groups[] = array('fb_id' => $fb_id, "fb_name" => $fb_name, "status" => $status);
			}
		}

		return $groups;
	}

	public function getNetworkSetting($user_id, $network){
		
		$twitter_setting = $this->conn->query("
			SELECT status FROM tbl_settings 
			WHERE uid = '$user_id' AND status = 1 AND network = '$network'
		");
		
		return $twitter_setting->num_rows;
	}

}
?>