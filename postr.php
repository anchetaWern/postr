<?php
session_start();
require_once('includes/header.php');
require_once('init.php');

if(empty($_SESSION['uid'])){
	header('Location: index.php');
}
?>
	<body>
		<div class="container">
			<span class="logout"><a id="logout" href="#"><?php echo $userInfo['email']; ?> [Logout]</a></span>
			<img src="assets/ajax-loader.gif" id="ajaxloader" style="display:none;"/>
			<div class="app_title">
				<img src="img/postr.png"/>
				<h2><a href="postr.php" class="link">Postr</a></h2>
			</div>
			
			<div class="form_container">
				<form class="custom" enctype="multipart/form-data" action="upload.php">
					<label for="status">What's Up?</label>
					<textarea name="status" id="status" style="height:100px;">
					</textarea>
					
					<a href="#" id="post_status" class="success button">Post</a>
					<a href="#" id="settings">
						<i class="icon foundicon-settings"></i>
					</a>
					<a href="#" id="upload">
						<i class="icon foundicon-paper-clip"></i>
					</a>
					<input type="file" name="photo" id="photo" style="display:none;"/>
					
					<div id="file_to_upload">
					
					</div>
					
					<div id="upload_response">
					
					</div>
					
					<div id="char_limit">
					140
					</div>
					
				</form> 
			</div><!--/.form_container-->
			
			
		</div><!--/.container-->
		
		<div id="settings_modal" class="reveal-modal medium">
			<h3>Settings</h3>
			<span>Where to Post?</span>
			<p>
				<form id="settings_form">
					<p>
						<label data-for="facebook">
							<img id="fb_pic" src="<?php echo $fbUserImg; ?>" width="48px" height="48px"/>
							<input type="checkbox" id="facebook">
							<a href="#" class="facebook_settings">Facebook</a>
							<span id="fb_user"><?php echo $fbUser; ?></span>
						
					</p>
					
					<p>
						<label data-for="twitter">
							<img id="twitter_pic" src="<?php echo $twitterUserImg; ?>" width="48px" height="48px"/>
							<input type="checkbox" id="twitter">
							<a href="#" class="twitter_settings">Twitter</a>
							<a href="<?php echo $twitterLoginText; ?>" id="twitter_login" class="login_links"> 
								<?php echo $twitterLoginText; ?>
							</a>
							<span id="twitter_user"><?php echo $twitterUserName; ?></span>
						</label>
					</p>

					<p>
						<label data-for="tumblr">
							<img id="tumblr_pic" src="<?php echo $tumblrPic; ?>" width="48px" height="48px"/>
							<input type="checkbox" id="tumblr">
							<a href="#" class="tumblr_settings">Tumblr</a>
							<a href="<?php echo $tumblrLogin; ?>" id="tumblr_login" class="login_links">
								<?php echo $tumblrLoginText; ?> 
							</a>
							<span id="twitter_user"><?php echo $tumblrUserName; ?></span>
						</label>
					</p>
				</form>
			</p>
			<p>
			<span>Multi-Post Mode</span>
				<form id="multipost_form">
					<p>
						<label data-for="multi_post">
							<input type="checkbox" id="multi_post">
							Yes
						</label>
					</p>
				</form>
			</p>
			
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#settings_modal-->
		


		<div id="facebook_modal" class="reveal-modal medium">
			<h3>Facebook Settings</h3>
			
			<dl class="tabs contained">
			  <dd class="active"><a href="#pages">Pages</a></dd>
			  <dd><a href="#groups">Groups</a></dd>
			  <dd><a href="#list">Lists</a></dd>
			</dl>
			
			<ul class="tabs-content contained">
			  <li class="active" id="pagesTab">
					<p>
						<label for="fb_pages">Pages</label>
						<input type="text" id="fb_pages"/>
					</p>
					<p>
						<div id="current_fb_pages">
							
						</div>
					</p>
					<p>
						<a href="#" id="add_fb_page" class="success button create_fb_lists" data-listtype="pages">Add Page</a>
					</p>
			  </li>
			  
			  <li id="groupsTab">
					<p>
						<label for="fb_groups">Groups</label>
						<input type="text" id="fb_groups"/>
					</p>
					<p>
						<div id="current_fb_groups">
							
						</div>
					</p>
					<p>
						<a href="#" id="add_fb_group" class="success button create_fb_lists" data-listtype="groups">Add Group</a>
					</p>
			  </li>

			  <li id="listTab">
					<p>
						<label for="fb_lists">Lists</label>
						<input type="text" id="fb_lists"/>
					</p>
					<p>
						<div id="current_fb_lists">
							
						</div>
					</p>
					<p>
						<a href="#" id="add_fb_list" class="success button create_fb_lists" data-listtype="lists">Add List</a>
					</p>
			  </li>
			</ul>
			
			<a href="#" id="back_to_settings" style="float:right;" class="button">Back to Settings</a>
			
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#facebook_modal-->
		
		<div id="tumblr_modal" class="reveal-modal medium">
			<h3>Tumblr Settings</h3>
			<span>Post Type</span>
			
			<ul class="tumblr_posttypes">
				<li data-postype="text">
					<i class="large-icons foundicon-edit">
						<p>Text</p>
					</i>
				</li>

				<li data-postype="photo">
					<i class="large-icons foundicon-photo">
						<p>Photo</p>
					</i>
				</li>
				<li data-postype="video">
					<i class="large-icons foundicon-video">
						<p>Video</p>
					</i>
				</li>
				<li data-postype="quote">
					<i class="large-icons foundicon-idea">
						<p>Quote</p>
					</i>
				</li>
			</ul>
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#tumblr_modal-->

		<div id="fb-root"></div>
	</body>
	
	
	
<?php
include('includes/footer.php');
?>	
	<script src="libs/foundation/javascripts/jquery.foundation.reveal.js"></script>
	<script src="libs/jqueryui/js/jquery-ui-1.8.24.custom.min.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.tabs.js"></script>
	
	<script>
		var users = new Store("users");
		var current_users = {};
		var current_user = {};
		var current_fb_page = {};
		var current_fb_group = {};
		var current_fb_list = {};
		var current_file = {};

		
		$('#status').val("");
	
		$(document).foundationTabs();
		
		var setCurrentFbUser = function(){
			$.post(
				'actions.php',
				{'action' : 'get_fbuser'},
				function(data){
					if(data){
						data = JSON.parse(data);
						current_user.fb_id = data['fb_id'];
						current_user.fb_name = data['fb_user'];
						current_user.fb_pic = data['fb_pic'];
					}
				}
			);
		};

		var getFbLoginStatus = function(){
			var loginstatus;

			$.ajax({
					type : 'post',
					url : 'actions.php',
					async: false,
					data : {"action" : "get_fbuser"}
				}
			).done(function(data){
				data = JSON.parse(data);
				loginstatus =  data['fb_status'];
			});
			return loginstatus;
		};

		var setFbSettings = function(fbLoginStatus){
			switch(fbLoginStatus){
				case 'verified_user':

					$('#fb_user').text(current_user.fb_name);
					$('#fb_pic').attr('src', current_user.fb_pic);
				break;

				case 'unknown_user':

					var fb_logoutlink = $("<a>").attr({
						"href" : "#", "class" : "logout_links", 
						"id" : "facebook_logout"
					}).text(' Logout');

					fb_logoutlink.insertAfter($('.facebook_settings'));

					$('#fb_user').empty();
					$('#fb_pic').attr('src', 'assets/system_img/fb.png');
				break;

				case 'no_user':

					var fb_loginlink = $("<a>").attr({
						"href" : "#", "class" : "login_links", 
						"id" : "facebook_login"
					}).text(' Login');

					fb_loginlink.insertAfter($('.facebook_settings'));

					$('#fb_user').empty();
					$('#fb_pic').attr('src', 'assets/system_img/fb.png');
				break;
			}
		};

		var buildFbSettings = function(fbID, fb_user, fb_status, fb_pic, fb_accessToken){
			$.post(
				'actions.php',
				{
					'action' : 'build_settings',
					'fb_id' : fbID,
					'fb_user' : fb_user,
					'fb_status' : fb_status,
					'fb_pic' : fb_pic,
					'fb_access' : fb_accessToken
				}
			);
		};

		setCurrentFbUser();
		var fbLoginStatus = getFbLoginStatus();
		setFbSettings(fbLoginStatus);
		

		$.post(
			'actions.php',
			{'action' : 'get_uid'},
			function(data){
				current_user.uid = data;
				if(!users.get('users')[current_user.uid]){
					$.post('actions.php', {'action' : 'load_settings'}, function(data){
  					var user_settings = JSON.parse(data);
  					current_users = user_settings;
  					
  					current_user.settings = current_users[current_user.uid]['settings'];
  					users.set('users', current_users);
  					loadSettings();
					});
				}else{
					current_user.settings = users.get('users')[current_user.uid]['settings'];

					//default tumblr settings
					current_user.settings.tumblr.posttype = "text";
					current_users[current_user.uid].settings.tumblr.posttype = "text";
					$('li[data-postype=text] i').addClass('enabled_icons');
					loadSettings();
				}
			}
		);

		var loadSettings = function(){
			$('#multi_post').attr('checked', !!parseInt(current_user.settings.multipost));
				
			var index = 0;
			for(var x in current_user.settings){
				
				var network = x;
				var network_status = Number(current_user.settings[x]['status']);
				
				if(network_status > 0){
					$($('#settings_form input[type=checkbox]')[index]).attr('checked', true);
					
				}
				index++;
			}

			var fb_pages = current_user.settings.facebook.pages;
			var fb_groups = current_user.settings.facebook.groups;
			var fb_lists = current_user.settings.facebook.lists;

			createFbLists(fb_pages, 'current_fb_pages', 'page', 'current_fb_pages');
			createFbLists(fb_groups, 'current_fb_groups', 'group', 'current_fb_groups');
			createFbLists(fb_lists, 'current_fb_lists', 'list', 'current_fb_lists');
			
			twitter_limit();
		};

		var createFbLists = function(listData, container, prefix, listClass){
			var listContainer = $('#' + container);
			for(var x in listData){
				var list_id = x;
				var fb_list = $("<div>");
				var list_name = $("<span>").text(listData[list_id][prefix + '_name']);
				var list_status = listData[list_id][prefix + '_status'];
				
				var list_checkbox = $("<input>").attr({
					"type" : "checkbox", 
					"id" : list_id, 
					"class" : listClass,
					"checked" : !!Number(list_status),
					"data-listtype" : prefix + 's'
				}).addClass('fblist');
				
				list_checkbox.appendTo(fb_list);
				list_name.appendTo(fb_list);
				
				listContainer.append(fb_list);
			}
		};



		var addFbList = function(fbLists, selectedFbList, listContainer, inputId, fbListType, fbClass, fbImage, prefix){
			if(!fbLists){
				fbLists = {};
			}

			//check if list has already been added before
			if(!!!fbLists[selectedFbList[fbListType + '_id']]){
				//list doesn't exist yet
				$('#' + inputId).val('');
				$('#' + inputId).focus();

				var current_fb_list = $('#' + listContainer);
				var fb_list = $("<div>");

				if(fbImage){
					var list_img = $("<img>").attr("src", selectedFbList[fbListType + '_pic']);
				}
				
				var list_name = $("<span>").text(selectedFbList[fbListType + '_name']);
				var list_checkbox = $("<input>").attr({
					"type" : "checkbox", 
					"id" : selectedFbList[fbListType + '_id'], 
					"class" : fbClass,
					"checked" : true,
					"data-listtype" : prefix,
				}).addClass('fblist');

				if(fbImage){
					fb_list.append(list_img);
				}
				
				fb_list.append(list_checkbox);
				fb_list.append(list_name);
				
				current_fb_list.append(fb_list);

				buildFbListSetting(prefix, selectedFbList, fbListType, fbImage); //saves into local storage
				createFbSetting(selectedFbList, fbListType, prefix); //saves into database

				noty_success.text = fbListType + " successfully added!";
				noty(noty_success);
			}else{ 
				//list already exists
				noty_err.text = fbListType + " has already been added before!";
				noty(noty_err);
			}		
		};

		var buildFbListSetting = function(prefix, selectedFbList, fbListType , fbImage){

			if(!current_user['settings']['facebook'][prefix]){
				current_user['settings']['facebook'][prefix] = {};
			}
			
			//update the current user
			var temp_userSettings = current_user['settings']['facebook'][prefix];

			temp_userSettings[selectedFbList[fbListType + '_id']] = {};
			temp_userSettings[selectedFbList[fbListType + '_id']][fbListType + "_name"] = selectedFbList[fbListType + '_name'];
			temp_userSettings[selectedFbList[fbListType + '_id']][fbListType + "_status"] = 1;

			if(fbImage){
				temp_userSettings[fbListType + "_img"] = selectedFbList[fbListType + '_pic'];
			}

			//copy the thing back
			current_user['settings']['facebook'][fbListType] = temp_userSettings;


			//if this is the first list to be added to local storage initialize it
			if(!current_users[current_user.uid]['settings']['facebook'][prefix]){
				current_users[current_user.uid]['settings']['facebook'][prefix] = {};
			}

			//update local storage
			current_users[current_user.uid]['settings']['facebook'][prefix] = temp_userSettings;

			users.set('users', current_users);
		};


		var createFbSetting = function(selectedFbList, fbListType, prefix){

			$.post('actions.php', {
					'action' : 'create_fb_settings',
					'type' : prefix,
					'fb_id' : selectedFbList[fbListType + '_id'], 
					'fb_name' : selectedFbList[fbListType + '_name'],
					'img_url' : selectedFbList[fbListType + '_pic']
			});

		};


		var loadFbData = function(){
			if(current_user.fb_id){
				FB.api('/' + current_user.fb_id, function(user){
					$('#fb_user').text(user.name);
					$('#fb_pic').attr('src', 'http://graph.facebook.com/'+ current_user.fb_id +'/picture?type=square');
				});


				FB.api('/'+ current_user.fb_id +'/groups', function(groups){
						var user_groups = groups.data;
						var data_source = [];
						
						for(var index in user_groups){
							var group_id = user_groups[index]['id'];
							var group_name = user_groups[index]['name'];
							data_source.push(
								{
								'value' : group_name,
								'group_id' : group_id,
								'group_name' : group_name
								}
							);
						}
						
						loadFbAutocomplete('fb_groups', 'group', JSON.stringify(data_source), current_fb_group);
				});


						
				FB.api({
					  method : 'fql.multiquery',
					  queries: {
						'q1' : 'SELECT page_id FROM page_admin WHERE uid = ' + current_user.fb_id,
						'q2' : 'SELECT page_id, name, pic_small, description FROM page WHERE page_id IN (SELECT page_id FROM #q1)'
					  }
					}, 
						function(data){
						
							var user_pages = data[1]['fql_result_set'];
							var data_source = [];
							for(var x in user_pages){
								var page_obj = user_pages[x];
								
								var page_id = page_obj['page_id'];
								var page_name = page_obj['name'];
								var page_description = page_obj['description'];
								var page_pic = page_obj['pic_small']
								
								data_source.push(
									{
									'value' : page_name, 'page_name' : page_name, 
									'page_id' : page_id, 'page_pic' : page_pic,
									'page_description' : page_description
									} 
								);
								
							}
							
							$('#fb_pages').autocomplete({
								source: data_source,
								select: function(event, ui){
									current_fb_page['page_id'] = ui['item']['page_id'];
									current_fb_page['page_description'] = ui['item']['page_description'];
									current_fb_page['page_name'] = ui['item']['page_name'];
									current_fb_page['page_pic'] = ui['item']['page_pic'];
								}
							}).data("autocomplete")._renderItem = function(ul, item){
								return $("<li></li>")
								.data("item.autocomplete", item)
								.append("<a id='"+  item.page_id +"'>" + "<img src='" + item.page_pic + "' />" + item.page_name+ "</a>" )
								.appendTo( ul );
							};
							
							
						}
				);

				FB.api('/' + current_user.fb_id +'/friendlists', function(friendlists){
					var user_friendlist = friendlists.data;
					var data_source = [];

					for(var index in user_friendlist){
						var list_id = user_friendlist[index]['id'];
						var list_name = user_friendlist[index]['name'];

						data_source.push({
							'value' : list_name,
							'list_id' : list_id,
							'list_name' : list_name	
						});
					}

					loadFbAutocomplete('fb_lists', 'list', JSON.stringify(data_source), current_fb_list);

				});
				
			}
		};

		
		if(!users.get('users')){
			//set users if it doesn't exists yet
			users.set('users', {});
		}else{
			current_users = users.get('users');
		}
	
		$('.facebook_settings').live('click', function(e){
			e.preventDefault();
			$('#facebook_modal').reveal();
		});

		$('.tumblr_settings').live('click', function(){
			$('#tumblr_modal').reveal();
		});

		$('#back_to_settings, #settings').click(function(e){
			e.preventDefault();
			$('#settings_modal').reveal();
		});
		
		$('#logout').click(function(e){
			e.preventDefault();
			$.post(
				'actions.php', 
				{'action' : 'logout'}, 
				function(){
					window.location.replace('index.php');
				}
			);
		});


		
		$('#post_status').click(function(e){
			e.preventDefault();
			var post_contents = {};
			var post = $.trim($('#status').val());
			var fbLoginStatus = getFbLoginStatus();

			checkNetworks();
			ajaxLoad();
			
			//comma-separated posts; only works for links
			if(parseInt(current_user.settings.multipost)){
				var posts = post.split(",");
					
					$.post(
						'actions.php', 
						{
							'action' : 'post_status',
							'fb_login_status' : fbLoginStatus,
							'status' : posts,
							'link' : posts,
							'file' : ''
						},
						function(response){
							
							if(response != ""){
								noty_err.text = response;
								noty(noty_err);
								
							}else{
								noty_success.text = "Status update successfuly posted!";
								noty(noty_success);
							}

							ajaxDone();
						}
					);
			

			}else{
			//single post
				var post_link = [];
				var longUrls = getLongUrls($('#status').val());


				if(longUrls.length > 1){
					post_link = longUrls;
				}else{
					post_link = get_link(post) || "";
				}


				$.post(
					'actions.php', 
					{
						'action' : 'post_status',
						'fb_login_status' : fbLoginStatus,
						'status' : post,
						'link' : post_link,
						'file' : current_file.filename,
						'long_urls' : longUrls
					},
					function(response){
						
						if(response != ""){
							noty_err.text = response;
							noty(noty_err);
							
						}else{
							noty_success.text = "Status update successfuly posted!";
							noty(noty_success);
						}
						ajaxDone();
					}
				);
 
			}
		});

		var checkNetworks = function(){
			var enabledFbSettings = $('#facebook_modal input:checked').length;
			var enabledNetworks = $('#settings_form input:checked').length;
			var totalEnabled = enabledFbSettings + enabledNetworks;

			if(totalEnabled == 0){
				noty_err.text = "You haven't selected a network to post your status update. Click on Settings and enable atleast one network";
				return false;
			}
		};

		var loadFbAutocomplete = function(autocompleteID, prefix, dataSource, currentList){
	  	$('#' + autocompleteID).autocomplete({
				source: JSON.parse(dataSource),
				select: function(event, ui){
					currentList[prefix + '_id'] = ui['item'][prefix + '_id'];
					currentList[prefix + '_name'] = ui['item'][prefix + '_name'];
				}
			});
	  };

	  var verifyFbUser = function(fbuser_id){
	  	var oauth_count;
	  	$.ajax({
					type : 'post',
					url : 'actions.php',
					async: false,
					data : {
						"action" : "verify_fbuser", "fbuser_id" : fbuser_id
					}
	  	}).done(function(data){
	  		oauth_count = data;

	  	});
	  	return oauth_count;
	  };

	  var updateFbAccessToken = function(fbAccessToken){
			if(current_user.fb_id){
				$.post(
					"actions.php", 
					{
					"action" : "update_oauth",	
					"provider" : "facebook", "oauth_id" : current_user.fb_id, 
					"oauth_token" : fbAccessToken,
					"username" : current_user.fb_name
					}
				);
			}
		};
		
		$('#settings_form input[type=checkbox]').click(function(){
			
			var network = $(this).attr('id');
			var status = Number(!!$(this).attr('checked'));
			
			if(!current_user['settings'][network]){
				current_user['settings'][network] = {};
			}

			current_user['settings'][network]['status'] = status;
			current_users[current_user.uid]['settings'][network]['status'] = status;
			users.set('users', current_users);
			
			$.post('actions.php', {'action' : 'update_settings', 'network' : network, 'status' : status});

			twitter_limit();
		});
		
		
		/*facebook*/
	  window.fbAsyncInit = function(){
	    FB.init({
	      appId      : '355248497890497',
	      status     : true, // check login status
	      cookie     : true // enable cookies to allow the server to access the session
	    });



		(function(){
			FB.getLoginStatus(function(response){
				
			  if (response.status === 'connected'){
			  	
			  	var fbAccessToken = FB.getAccessToken();
					updateFbAccessToken(fbAccessToken);
			  	loadFbData();

			  } 
		 	});
				
		})();
		
	  };

	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));

	  $('#facebook_logout').live('click', function(){
	  	FB.logout(function(){
	  		noty_success.text = "Facebook user successfully logged out!";
	  		noty(noty_success);

  			var fb_loginlink = $("<a>").attr({
					"href" : "#", "class" : "login_links", 
					"id" : "facebook_login"
				}).text(' Login');

				fb_loginlink.insertAfter($('.facebook_settings'));

				$('#fb_user').empty();
				$('#fb_pic').attr('src', 'assets/system_img/fb.png');
	  
	  		$("#facebook_logout").remove();
	  	});
	  });

		$('#facebook_login').live('click', function(e){
			e.preventDefault();
			FB.login(function(response){
				if(response.authResponse){
					FB.api('/me', function(user){
						
						var verifiedFbUser;

						FB.api({
							method : 'fql.query',
							query : 'SELECT name, pic_small FROM user WHERE uid=me()'
						}, function(data){
								var fb_user = data[0]['name'];
								var fb_status = "verified_user";
								var fb_pic = data[0]['pic_small'];

								FB.getLoginStatus(function(response){
									 if(response.status === "connected"){
									 		var access_token = FB.getAccessToken();
									 		buildFbSettings(user.id, fb_user, "verified_user", fb_pic, access_token);
									 }else if(response.status === "not_authorized"){

									 		buildFbSettings("", "", "unknown_user", "assets/system_img/fb.png", "");
									 }else{

									 		buildFbSettings("", "", "no_user", "assets/system_img/fb.png", "");
									 }

									var fbLoginStatus = getFbLoginStatus();
									setFbSettings(fbLoginStatus);

									verifiedFbUser = verifyFbUser(user.id);

									if(parseInt(verifiedFbUser)){
										
										current_user.fb_id = user.id;
										current_user.fb_name = user.name;

										var fbAccessToken = FB.getAccessToken();
										updateFbAccessToken(fbAccessToken);
										loadFbData();
										$('#facebook_login').hide();
									}
								});

						});


					});
				}
			}, {scope : 'user_about_me,email,read_friendlists,publish_stream,manage_pages,user_groups,user_photos'});
		});


		$('.create_fb_lists').click(function(e){
			e.preventDefault();
			var listType = $(this).data('listtype');
			var listTypeLen = listType.length;
			var listTypeNos = listType.substring(listTypeLen - 1, -1);
			

			var fbLists = current_user.settings.facebook[listType];
			var selectedFbList = window['current_fb_' + listTypeNos];
			
			var listContainer = 'current_fb_' +  listType;
			var fbClass = listContainer;

			var fbImage = 1;
			if(listType != 'pages'){
				fbImage = 0;
			}

			var inputId = 'fb_' + listType;

			addFbList(fbLists, selectedFbList, listContainer, inputId, listTypeNos, fbClass, fbImage, listType);
		});
		
		$('.current_fb_pages').live('click', function(){
			//change status whether to post to the currently selected facebook page or not
			var page_id = $(this).attr('id');
			var page_status = Number(!!$(this).attr('checked'));
			current_user['settings']['facebook']['pages'][page_id]['page_status'] = page_status; 
			
			current_users[current_user.uid]['settings']['facebook']['pages'][page_id]['page_status'] = page_status;
			users.set('users', current_users);

			$.post('actions.php', 
					{
						'action' : 'update_fbsetting', 
						'fb_id' : page_id, 'status' : page_status
					}
			);

		});
		
		$('.fblist').live('click', function(){
			//change status whether to post to the currently selected facebook group or not
			var list_id = $(this).attr('id');
			var list_status = Number(!!$(this).attr('checked'));
			var list_type = $(this).data('listtype');
			var prefix = list_type.substring(list_type.length - 1, -1);
			
			updateFbListStatus(list_type, prefix, list_id, list_status);
		});

		var updateFbListStatus = function(listType, prefix, listId, listStatus){

			current_user['settings']['facebook'][listType][listId][prefix + '_status'] = listStatus; 
			
			current_users[current_user.uid]['settings']['facebook'][listType][listId][prefix + '_status'] = listStatus;
			users.set('users', current_users);

			$.post('actions.php', 
					{
						'action' : 'update_fbsetting', 
						'fb_id' : listId, 'status' : listStatus
					}
			);
		};
		
		$('#multi_post').click(function(){
			var status = Number(!!$(this).attr('checked'));
			current_user['settings']['multipost'] = status;
			
			current_users[current_user.uid]['settings']['multipost'] = status;
			users.set('users', current_users);

			$.post('actions.php', {'action' : 'multipost', 'status' : status});
		});

		var getTumblrPostType = function(status){

			var hasPhoto !!$('#file_to_upload').children().length;
			var hasText = !!status;
			var hasQuote = !!/"+\w+(\w|\s)+"/.test(status);

			var videoRegex = new RegExp("^(http|https)://(youtu|www.youtube|vimeo|youtube)\.(be|com)/[A-Za-z0-9\?&=]+$");
			var hasVideo = !!videoRegex.test(status);

		};
		
		$('#status').keydown(function(){
			remaining_chars();
		});
		
		$('#status').keyup(function(){
			remaining_chars();
		});
		
		$('#upload').click(function(e){
			e.preventDefault();
			$('#photo').click();
		});
		
		$('#photo').change(function(){
			var filepath = $(this).val();
			var filepath_r = filepath.split("\\");
			$('#filename').text(filepath_r[filepath_r.length - 1]);
			
			(function(){  
				if($('#photo')[0].files[0]){
					var file = $('#photo')[0].files[0];
					if (!!file.type.match(/image.*/)){  

						var formdata = false;  
						if(window.FormData){  
							formdata = new FormData();  

							if(window.FileReader){  
								reader = new FileReader();  
								reader.onloadend = function(e){  
									showUploadedItem(e.target.result);  
									
								};  
								reader.readAsDataURL(file);  
							}  
							if(formdata){  
								formdata.append("images", file);  
								
								$.ajax({  
									url: "upload.php",  
									type: "POST",  
									data: formdata,  
									processData: false,  
									contentType: false,  
									success: function(response){
										var response_data = JSON.parse(response);
										if(response_data['status'] == 0){
										
											noty_err.text = response_data['response'];
											noty(noty_err);

											current_file.filename = "";
											remaining_chars();
											
										}else{
											noty_success.text = response_data['response'];
											noty(noty_success);
											
											current_file.filename = response_data['filename'];
											remaining_chars();
										}
									}  
								});  
							} 

						}
					}else{
						noty_err.text = 'The uploaded file was not an image!';
						noty(noty_err);
						current_file.filename = "";
						$('#file_to_upload').empty();
						remaining_chars();
					}
				}
			})(); 
			

			
		});
		
		function showUploadedItem(source){  
			var img_container = $('#file_to_upload')[0];
			var	img  = $("<img>").attr({'src' : source, 'id' : 'uploaded_image'});  
			
			$(img_container).html(img);
		}  

		
		var remaining_chars = function(){
			
			var status = $('#status').val();
			var current_length = status.length;
			var char_limit = 140;
			var shortUrlLength = 20;
			var uploadfile = $('#file_to_upload').children().length;

			var shortUrlCount = getShortUrlCount(status);
			var shortUrlTotalLength = shortUrlCount * shortUrlLength;

			var longUrls = getLongUrls(status);
			var longUrlLength = getLongUrlLength(longUrls);
			var nonUrlLength = current_length - longUrlLength;

			var totalLength = shortUrlTotalLength + nonUrlLength;

			if(uploadfile == 1){
				var remaining_char = char_limit - totalLength - 21;
			}else{
				var remaining_char = char_limit - totalLength;
			}
			
			$('#char_limit').text(remaining_char);
			
			if(remaining_char <= 10){
				$('#char_limit').css('color', 'red');
			}else{
				$('#char_limit').css('color', 'black');
			}
		};
		
		var get_link = function(post){
			var url = ''; 
			var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi
			var regex = new RegExp(expression);
			
			if(post.match(regex)){
				url = regex.exec(post)[0]; 
			}
			return url;
		};

		var getShortUrlCount = function(status){
			var urlcount = 0; 
			var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi
			var regex = new RegExp(expression);
			
			if(status.match(regex)){
				urlcount = status.match(regex).length;
			}
			return parseInt(urlcount);
		};

		var getLongUrls = function(status){
			var longUrls = []; 
			var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi
			var regex = new RegExp(expression);
			
			if(status.match(regex)){
				longUrls = status.match(regex);
			}
			return longUrls;
		};

		var getLongUrlLength = function(longUrls){
			var longUrlLength = 0;
			for(var n in longUrls){
				longUrlLength = parseInt(longUrlLength) + longUrls[n].length;
			}
			return longUrlLength;
		};
		
		var twitter_limit = function(){
			if(current_user.settings.twitter.status){
				$('#char_limit').show();
			}else{
				$('#char_limit').hide();
			}
		};


		$('.tumblr_posttypes li').click(function(){
			var posttype = $(this).data('postype');
			current_user.settings.tumblr.posttype = posttype;
			current_users[current_user.uid].settings.tumblr.posttype = posttype;

			$('.tumblr_posttypes li i').removeClass('enabled_icons');
			$(this).children('i').addClass('enabled_icons');
		});
		
	</script>
</html>
 