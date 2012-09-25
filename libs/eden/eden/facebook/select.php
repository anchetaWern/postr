<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Generates select query string syntax
 *
 * @package    Eden
 * @category   sql
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Eden_Facebook_Select extends Eden_Class {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_select 	= NULL;
	protected $_from 	= NULL;
	protected $_where 	= array();
	protected $_sortBy	= array();
	protected $_page 	= NULL;
	protected $_length	= NULL;
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	public static function i() {
		return self::_getMultiple(__CLASS__);
	}
	
	public function __toString() {
		return $this->getQuery();
	}
	
	/* Public Methods
	-------------------------------*/
	
	/**
	 * From clause
	 *
	 * @param string from
	 * @return this
	 * @notes loads from phrase into registry
	 */
	public function from($from) {
		//Argument 1 must be a string
		Eden_Facebook_Error::i()->argument(1, 'string');
		
		$this->_from = $from;
		return $this;
	}
	
	/**
	 * Limit clause
	 *
	 * @param string|int page
	 * @param string|int length
	 * @return this
	 * @notes loads page and length into registry
	 */
	public function limit($page, $length) {
		//argument test
		Eden_Facebook_Error::i()
			->argument(1, 'numeric')	//Argument 1 must be a number
			->argument(2, 'numeric');	//Argument 2 must be a number
		
		$this->_page = $page;
		$this->_length = $length; 

		return $this;
	}
	
	/**
	 * Returns the string version of the query 
	 *
	 * @param  bool
	 * @return string
	 * @notes returns the query based on the registry
	 */
	public function getQuery() {
		$where = empty($this->_where) ? '' : 'WHERE '.implode(' AND ', $this->_where);
		$sort = empty($this->_sortBy) ? '' : 'ORDER BY '.implode(', ', $this->_sortBy);
		$limit = is_null($this->_page) ? '' : 'LIMIT ' . $this->_page .',' .$this->_length;
		
		if(empty($this->_select) || $this->_select == '*') {
			$this->_select = implode(', ', self::$_columns[$this->_from]);
		}
		
		$query = sprintf(
			'SELECT %s FROM %s %s %s %s;',
			$this->_select, $this->_from, 
			$where, $sort, $limit);
		
		return str_replace('  ', ' ', $query);
	}
	
	/**
	 * Select clause
	 *
	 * @param string select
	 * @return this
	 * @notes loads select phrase into registry
	 */
	public function select($select = '*') {
		//Argument 1 must be a string or array
		Eden_Facebook_Error::i()->argument(1, 'string', 'array');
		
		//if select is an array
		if(is_array($select)) {
			//transform into a string
			$select = implode(', ', $select);
		}
		
		$this->_select = $select;
		
		return $this;
	}
	
	/**
	 * Order by clause
	 *
	 * @param string field
	 * @param string order
	 * @return this
	 * @notes loads field and order into registry
	 */
	public function sortBy($field, $order = 'ASC') {
		//argument test
		Eden_Facebook_Error::i()
			->argument(1, 'string')		//Argument 1 must be a string
			->argument(2, 'string'); 	//Argument 2 must be a string
		
		$this->_sortBy[] = $field . ' ' . $order;
		
		return $this;
	}
	
	/**
	 * Where clause
	 *
	 * @param array|string where
	 * @return	this
	 * @notes loads a where phrase into registry
	 */
	public function where($where) {
		//Argument 1 must be a string or array
		Eden_Facebook_Error::i()->argument(1, 'string', 'array');
		
		if(is_string($where)) {
			$where = array($where);
		}
		
		$this->_where = array_merge($this->_where, $where); 
		
		return $this;
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
	/* Large Data
	-------------------------------*/
	protected static $_columns = array(
		'album'	=> array(
			'aid',			'object_id',		'owner',
			'cover_pid',	'cover_object_id',	'name',
			'created',		'modified',			'description',
			'location',		'size',				'link',
			'visible',		'modified_major',	'edit_link',
			'type',			'can_upload',		'photo_count',
			'video_count'),
		'application' => array(
			'app_id',							'api_key',
			'canvas_name',						'display_name',
			'icon_url',							'logo_url',
			'company_name',						'developers',
			'description',						'daily_active_users',
			'weekly_active_users',				'monthly_active_users',
			'category',							'subcategory',
			'is_facebook_app',					'restriction_info',
			'app_domains',						'auth_dialog_data_help_url',
			'auth_dialog_description',			'auth_dialog_headline',
			'auth_dialog_perms_explanation',	'auth_referral_user_perms',
			'auth_referral_friend_perms',		'auth_referral_default_activity_privacy',
			'auth_referral_enabled',			'auth_referral_extended_perms',
			'auth_referral_response_type',		'canvas_fluid_height',
			'canvas_fluid_width',				'canvas_url',
			'contact_email',					'created_time',
			'creator_uid',						'deauth_callback_url',
			'iphone_app_store_id',				'hosting_url',
			'mobile_web_url',					'page_tab_default_name',
			'page_tab_url',						'privacy_policy_url',
			'secure_canvas_url',				'secure_page_tab_url',
			'server_ip_whitelist',				'social_discovery',
			'terms_of_service_url',				'update_ip_whitelist',
			'user_support_email',				'user_support_url',
			'website_url'),
		'apprequest' => array(
			'request_id',	'app_id',		'recipient_uid',	
			'sender_uid',	'message',		'data',
			'created_time'),
		'checkin' => array(
			'checkin_id',	'author_uid',	'page_id',			
			'app_id',		'post_id',		'coords',
			'timestamp',	'tagged_uids',	'message'),
		'comment' => array(
			'xid', 			'object_id', 	'post_id', 
			'fromid', 		'time', 		'text', 
			'id', 			'username ', 	'reply_xid', 
			'post_fbid', 	'app_id', 		'likes', 
			'comments', 	'user_likes', 	'is_private'),
		'comments_info' => array(
			'app_id', 		'xid', 
			'count', 		'updated_time'),
		'connection' => array(
			'source_id', 	'target_id', 
			'target_type', 	'is_following'),
		'cookies' => array(
			'uid', 			'name', 		'value', 		
			'expires', 		'path'),
		'developer' 	=> array('developer_id', 'application_id', 'role'),
		'domain' 		=> array('domain_id', 'domain_name'),
		'domain_admin' 	=> array('owner_id', 'domain_id'),
		'event' => array(
			'eid', 			'name', 			'tagline', 
			'nid', 			'pic_small', 		'pic_big', 
			'pic_square', 	'pic', 				'host', 
			'description', 	'event_type', 		'event_subtype', 
			'start_time', 	'end_time', 		'creator', 
			'update_time', 	'location', 		'venue', 
			'privacy', 		'hide_guest_list', 	'can_invite_friends'),
		'event_member' => array(
			'uid', 			'eid', 
			'rsvp_status', 	'start_time'),
		'family' => array(
			'profile_id', 	'uid', 
			'name', 		'birthday', 
			'relationship'),
		'friend' 			=> array('uid1', 'uid2'),
		'friend_request' 	=> array(
			'uid_to', 		'uid_from', 
			'time', 		'message', 
			'unread'),
		'friendlist' 		=> array('owner', 'flid', 'name'),
		'friendlist_member' => array('flid', 'uid'),
		'group' 			=> array(
			'gid', 			'name', 		'nid', 
			'pic_small', 	'pic_big', 		'pic', 
			'description', 	'group_type', 	'group_subtype', 
			'recent_news', 	'creator', 		'update_time', 
			'office', 		'website', 		'venue', 
			'privacy', 		'icon', 		'icon34', 
			'icon68', 		'email', 		'version'),
		'group_member' => array(
			'uid', 			'gid', 			'administrator', 
			'positions', 	'unread', 		'bookmark_order'),
		'insights' 			=> array('object_id', 'metric', 'end_time', 'period', 'value'),
		'like' 				=> array('object_id', 'post_id', 'user_id', 'object_type'),
		'link' => array(
			'link_id', 		'owner', 		'owner_comment', 
			'created_time', 'title', 		'summary', 
			'url', 			'picture', 		'image_urls'),
		'link_stat' => array(
			'url', 			'normalized_url', 	'share_count', 
			'like_count', 	'comment_count', 	'total_count', 
			'click_count', 	'comments_fbid', 	'commentsbox_count'),
		'mailbox_folder' => array(
			'folder_id', 	'viewer_id', 		'name', 
			'unread_count', 'total_count'),
		'message' => array(
			'message_id', 	'thread_id', 		'author_id', 
			'body', 		'created_time', 	'attachment', 
			'viewer_id'),
		'note' => array(
			'uid', 			'note_id', 		'created_time', 
			'updated_time', 'content', 		'content_html', 
			'title'),
		'notification' => array(
			'notification_id', 		'sender_id', 	'recipient_id', 
			'created_time', 		'updated_time', 'title_html', 
			'title_text', 			'body_html', 	'body_text', 
			'href', 				'app_id', 		'is_unread', 
			'is_hidden',	 		'object_id', 	'object_type', 
			'icon_url'),
		'object_url' => array(
			'url', 					'id', 	
			'type', 				'site'),
		'page' => array(
			'page_id', 				'name', 					'username', 
			'description', 			'categories', 				'is_community_page', 
			'pic_small', 			'pic_big', 					'pic_square', 
			'pic', 					'pic_large', 				'page_url', 
			'fan_count', 			'type', 					'website', 
			'has_added_app', 		'general_info', 			'can_post', 
			'checkins', 			'founded', 					'company_overview', 
			'mission', 				'products', 				'location', 
			'parking', 				'hours', 					'pharma_safety_info', 
			'public_transit', 		'attire', 					'payment_options', 
			'culinary_team', 		'general_manager', 			'price_range', 
			'restaurant_services', 	'restaurant_specialties', 	'phone', 
			'release_date', 		'genre', 					'starring', 
			'screenplay_by', 		'directed_by', 				'produced_by', 
			'studio', 				'awards', 					'plot_outline', 
			'season', 				'network', 					'schedule', 
			'written_by', 			'band_members', 			'hometown', 
			'current_location', 	'record_label', 			'booking_agent', 
			'press_contact', 		'artists_we_like', 			'influences', 
			'band_interests', 		'bio', 						'affiliation', 
			'birthday', 			'personal_info', 			'personal_interests', 
			'built', 				'features', 				'mpg'),
		'page_admin' 		=> array('uid', 'page_id', 'type'),
		'page_blocked_user' => array('page_id', 'uid'),
		'page_fan' => array(
			'uid', 					'page_id', 
			'type', 				'profile_section', 
			'created_time'),
		'permissions' 		=> array('uid', 'PERMISSION_NAME'),
		'permissions_info' 	=> array('permission_name', 'header', 'summary'),
		'photo' => array(
			'pid', 				'aid', 				'owner', 
			'src_small', 		'src_small_width', 	'src_small_height', 
			'src_big', 			'src_big_width', 	'src_big_height', 
			'src', 				'src_width', 		'src_height', 
			'link', 			'caption', 			'created', 
			'modified', 		'position', 		'object_id', 
			'album_object_id', 	'images'),
		'photo_tag' => array(
			'pid', 				'subject', 			'object_id', 
			'text', 			'xcoord', 			'ycoord', 
			'created'),
		'place' => array(
			'page_id', 			'name', 			'description', 
			'geometry', 		'latitude', 		'longitude', 
			'checkin_count', 	'display_subtext'),
		'privacy' => array(
			'id', 				'object_id', 		'value ', 
			'description', 		'allow', 			'deny', 
			'owner_id', 		'networks', 		'friends'),
		'privacy_setting' => array(
			'name', 			'value ', 			'description', 
			'allow', 			'deny', 			'networks', 
			'friends'),
		'profile' => array(
			'id', 				'can_post', 		'name', 
			'url', 				'pic', 				'pic_square', 
			'pic_small', 		'pic_big', 			'pic_crop', 
			'type', 			'username'),
		'question' => array(
			'id', 				'owner', 			'question', 
			'created_time', 	'updated_time'),
		'question_option' => array(
			'id', 				'question_id', 		'name', 
			'votes', 			'object_id', 		'owner', 
			'created_time'),
		'question_option_votes' => array('option_id', 'voter_id'),
		'review' => array(
			'reviewee_id', 		'reviewer_id', 		'review_id', 
			'message', 			'created_time', 	'rating'),
		'standard_friend_info' => array('uid1', 'uid2'),
		'standard_user_info' => array(
			'uid', 				'name', 			'username', 
			'third_party_id', 	'first_name', 		'last_name', 
			'locale', 			'affiliations', 	'profile_url', 
			'timezone', 		'birthday', 		'sex', 
			'proxied_email', 	'current_location', 'allowed_restrictions'),
		'status' => array(
			'uid', 				'status_id', 
			'time', 			'source', 
			'message'),
		'stream' => array(
			'post_id', 			'viewer_id ', 		'app_id', 
			'source_id ', 		'updated_time', 	'created_time', 
			'filter_key', 		'attribution ', 	'actor_id', 
			'target_id', 		'message', 			'app_data', 
			'action_links', 	'attachment', 		'impressions', 
			'comments', 		'likes', 			'privacy',
			'permalink', 		'xid', 				'tagged_ids', 
			'message_tags', 	'description', 		'description_tags'),
		'stream_filter' => array(
			'uid', 				'filter_key ', 		'name', 
			'rank ', 			'icon_url', 		'is_visible', 
			'type', 			'value'),
		'stream_tag' => array('post_id', 'actor_id', 'target_id'),
		'thread' => array(
			'thread_id', 		'folder_id', 		'subject', 
			'recipients', 		'updated_time', 	'parent_message_id', 
			'parent_thread_id', 'message_count', 	'snippet', 
			'snippet_author', 	'object_id', 		'unread', 
			'viewer_id'),
		'translation' => array(
			'locale', 			'native_hash', 		'native_string', 
			'description', 		'translation', 		'approval_status', 
			'pre_hash_string', 	'best_string'),
		'unified_message' => array(
			'message_id', 		'thread_id', 		'subject', 
			'body', 			'unread', 			'action_id', 
			'timestamp', 		'tags', 			'sender', 
			'recipients', 		'object_sender', 	'html_body', 
			'attachments', 		'attachment_map', 	'shares', 
			'share_map'),
		'unified_thread' => array(
			'action_id', 			'archived', 					'can_reply', 
			'folder', 				'former_participants', 			'has_attachments', 
			'is_subscribed', 		'last_visible_add_action_id', 	'name', 
			'num_messages', 		'num_unread', 					'object_participants', 
			'participants', 		'senders', 						'single_recipient', 
			'snippet', 				'snippet_sender', 				'snippet_message_has_attachment', 
			'subject', 				'tags', 						'thread_id', 
			'thread_participants', 	'timestamp', 					'unread'),
		'unified_thread_action' => array(
			'action_id', 			'actor', 		'thread_id', 
			'timestamp', 			'type', 		'users'),
		'unified_thread_count' => array(
			'folder', 				'unread_count', 	'unseen_count', 
			'last_action_id', 		'last_seen_time', 	'total_threads'),
		'url_like' => array('user_id', 'url'),
		'user' => array(
			'uid', 						'username', 			'first_name', 
			'middle_name', 				'last_name', 			'name', 
			'pic_small', 				'pic_big', 				'pic_square', 
			'pic', 						'affiliations', 		'profile_update_time', 
			'timezone', 				'religion', 			'birthday', 
			'birthday_date', 			'sex', 					'hometown_location', 
			'meeting_sex', 				'meeting_for', 			'relationship_status', 
			'significant_other_id', 	'political', 			'current_location', 
			'activities', 				'interests', 			'is_app_user', 
			'music', 					'tv', 					'movies', 
			'books', 					'quotes', 				'about_me',
			'hs_info', 					'education_history', 	'work_history', 
			'notes_count', 				'wall_count', 			'status', 
			'has_added_app',	 		'online_presence', 		'locale', 
			'proxied_email', 			'profile_url', 			'email_hashes', 
			'pic_small_with_logo', 		'pic_big_with_logo', 	'pic_square_with_logo', 
			'pic_with_logo', 			'allowed_restrictions', 'verified', 
			'profile_blurb', 			'family', 				'website', 
			'is_blocked', 				'contact_email', 		'email', 
			'third_party_id', 			'name_format', 			'video_upload_limits', 
			'games', 					'is_minor', 			'work', 
			'education', 				'sports', 				'favorite_athletes', 
			'favorite_teams', 			'inspirational_people', 'languages', 
			'likes_count', 				'friend_count', 		'mutual_friend_count', 
			'can_post'),
		'video' => array(
			'vid', 				'owner', 		'album_id', 
			'title', 			'description', 	'link', 
			'thumbnail_link', 	'embed_html', 	'updated_time', 
			'created_time', 	'length', 		'src', 
			'src_hq'),
		'video_tag' => array('vid', 'subject', 'updated_time', 'created_time'));
}