<?php
session_start();
include('includes/header.php');
if(empty($_SESSION['uid'])){
header('Location: index.php');
}
?>
	<body>
		<div class="container">
			<span class="logout"><a id="logout" href="#"><?php echo $_SESSION['email']; ?> [Logout]</a></span>
			<div class="app_title">
				<img src="img/postr.png"/>
				<h2><a href="postr.php" class="link">Postr</a></h2>
			</div>
			
			<div class="form_container">
				<form class="custom">
					<label for="status">What's Up?</label>
					<textarea name="status" id="status" style="height:100px;">
					</textarea>
					
					<a href="#" id="post_status" class="success button">Post</a>
					<a href="#" id="settings">Settings</a>
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
							<input type="checkbox" id="facebook">
							<a href="#" class="network_settings">Facebook</a>
						</label>
						
					</p>
					<p>
						<label data-for="gplus">
							<input type="checkbox" id="gplus" >
							<a href="#" class="network_settings">Google+</a>
						</label>
					</p>
					<p>
						<label data-for="linked_in">
							<input type="checkbox" id="linked_in">
							<a href="#" class="network_settings">LinkedIn</a>
						</label>
					</p>
					<p>
						<label data-for="twitter">
							<input type="checkbox" id="twitter">
							<a href="#" class="network_settings">Twitter</a>
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
					<a href="#" id="add_fb_page" class="success button">Add Page</a>
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
					<a href="#" id="add_fb_group" class="success button">Add Group</a>
				</p>
			  </li>
			</ul>
			
			<a href="#" id="back_to_settings" style="float:right;" class="button">Back to Settings</a>
			
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#facebook_modal-->
		
		<div id="fb-root"></div>
	</body>
	
	
	
<?php
include('includes/footer.php');
?>	
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.reveal.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.tabs.js"></script>
	
	<script>
		var users = new Store("users");
		var current_users = {};
		var current_user = {};
		var current_fb_page = {};
		var current_fb_group = {};
		
		$('#status').val("");
	
		$(document).foundationTabs();
		
		$.post(
			'actions/actions.php',
			{'action' : 'get_uid'},
			function(data){
				current_user.uid = data;
				current_user.settings = users.get('users')[current_user.uid]['settings'];
				
				$('#multi_post').attr('checked', !!current_user.settings.multipost);
				
				var index = 0;
				for(var x in current_user.settings){
					
					var network = x;
					var network_status = current_user.settings[x]['status'];
					
					if(network_status != 0){
						$($('#settings_form input[type=checkbox]')[index]).attr('checked', true);
						
						switch(network){
							case 'facebook':
								fb_login();
							break;
						}
						
					}
					index++;
				}
				
				/*load current facebook pages*/
				var fb_pages = current_user.settings.facebook.pages;
				var fb_pages_container = $('#current_fb_pages');
				
				
				for(var x in fb_pages){
					var page_id = x;
					
					var fb_page = $("<div>");
					var page_img = $("<img>").attr("src", fb_pages[page_id]['page_img']);
					var page_name = $("<span>").text(fb_pages[page_id]['page_name']);
					var page_status = fb_pages[page_id]['page_status'];
					
					var page_checkbox = $("<input>").attr({
						"type" : "checkbox", 
						"id" : page_id, 
						"class" : "current_fb_pages",
						"checked" : !!page_status
					});
					
					
					page_img.appendTo(fb_page);
					page_checkbox.appendTo(fb_page);
					page_name.appendTo(fb_page);
					
					fb_pages_container.append(fb_page);
				}
				
				/*load current facebook groups*/
				var fb_groups = current_user.settings.facebook.groups;
				var fb_groups_container = $('#current_fb_groups');
				
				for(var x in fb_groups){
					var group_id = x;
					console.log(group_id);
					
					var fb_group = $("<div>");
					var group_name = $("<span>").text(fb_groups[group_id]['group_name']);
					var group_status = fb_groups[group_id]['group_status'];
					
					var group_checkbox = $("<input>").attr({
						"type" : "checkbox", 
						"id" : group_id, 
						"class" : "current_fb_groups",
						"checked" : !!group_status
					});
					
					group_checkbox.appendTo(fb_group);
					group_name.appendTo(fb_group);
					
					fb_groups_container.append(fb_group);
				}
				
				
				
			}
		);
		
		if(!users.get('users')){
			//set users if it doesn't exists yet
			users.set('users', {});
		}else{
			current_users = users.get('users');
		}
	
		$('.network_settings').live('click', function(e){
			e.preventDefault();
			$('#facebook_modal').reveal();
		});
		
		$('#back_to_settings').click(function(e){
			e.preventDefault();
			$('#settings_modal').reveal();
		});
		
		$('#settings').click(function(e){
			e.preventDefault();
			$('#settings_modal').reveal();
		});
	
		$('#logout').click(function(e){
			e.preventDefault();
			$.post(
				'actions/actions.php', 
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
			
			if(current_user.settings.multipost){//rapidfire
				var posts = post.split(",");
				var posts_length = posts.length;
				for(var i = 0; i <= posts_length; i++){
					console.log(posts[i]);
					post_contents = {
						message : posts[i],
						link : posts[i]
					};
					
					fb_post(post_contents);
				}
				
			}else{
				
				var post_link = get_link(post);
				post_contents = {
					message : post,
					link : post_link
				};
				
				fb_post(post_contents);
			}
		});
		
		$('#settings_form input[type=checkbox]').click(function(){
			
			var network = $(this).attr('id');
			var status = Number(!!$(this).attr('checked'));
			
			current_user['settings'][network] = {};
			current_user['settings'][network]['status'] = status;
			current_users[current_user.uid]['settings'][network]['status'] = status;
			users.set('users', current_users);
			
		});
		
		
		/*facebook*/
		FB.init({appId: "355248497890497", status: true, cookie: true});
		
		var fb_login = function(){
			FB.login(
				function(response){
					FB.api({
					  method : 'fql.multiquery',
					  queries: {
						'q1' : 'SELECT page_id FROM page_admin WHERE uid = me()',
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
					
					FB.api('/me/groups', function(groups){
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
						
						$('#fb_groups').autocomplete({
							source: data_source,
							select: function(event, ui){
								current_fb_group['group_id'] = ui['item']['group_id'];
								current_fb_group['group_name'] = ui['item']['group_name'];
							}
						});
					});
				}, 
				{scope: 'user_about_me,email,read_friendlists,publish_stream,manage_pages,user_groups'}
			);
		
		};
		
		$('#add_fb_page').click(function(e){
			e.preventDefault();
			
			if(!current_user.settings.facebook.pages){
				current_user.settings.facebook.pages = {};
			}
			
			if(!!!current_user.settings.facebook.pages[current_fb_page['page_id']]){
				$('#fb_pages').val('');
				
				var current_fb_pages = $('#current_fb_pages');
				var fb_page = $("<div>");
				
				var page_img = $("<img>").attr("src", current_fb_page['page_pic']);
				var page_name = $("<span>").text(current_fb_page['page_name']);
				var page_checkbox = $("<input>").attr({
					"type" : "checkbox", 
					"id" : current_fb_page['page_id'], 
					"class" : "current_fb_pages",
					"checked" : true
				});
				
				fb_page.append(page_img);
				fb_page.append(page_checkbox);
				fb_page.append(page_name);
				
				current_fb_pages.append(fb_page);
				
				if(!current_user['settings']['facebook']['pages']){
					current_user['settings']['facebook']['pages'] = {};
				}
				
				current_user['settings']['facebook']['pages'][current_fb_page['page_id']] = {
					"page_name" : current_fb_page['page_name'], 
					"page_img" : current_fb_page['page_pic']
				};
				
				if(!current_users[current_user.uid]['settings']['facebook']['pages']){
					current_users[current_user.uid]['settings']['facebook']['pages'] = {};
				}
				
				current_users[current_user.uid]['settings']['facebook']['pages'][current_fb_page['page_id']] = {
					"page_name" : current_fb_page['page_name'], 
					"page_img" : current_fb_page['page_pic'],
					"page_status" : 1
				};
				
				users.set('users', current_users);
				
				noty_success.text = 'Facebook Page Successfully Added!';
				noty(noty_success);
			}else{
				noty_err.text = 'The selected Facebook Page has already been added before!';
				noty(noty_err);
			}
		});
		
		$('#add_fb_group').click(function(e){
			e.preventDefault();
				if(!current_user.settings.facebook.groups){
					current_user.settings.facebook.groups = {};
				}
				
				if(!!!current_user.settings.facebook.groups[current_fb_group['group_id']]){
					$('#fb_groups').val('');
					
					var current_fb_groups = $('#current_fb_groups');
					var fb_group = $("<div>");
					
					var group_name = $("<span>").text(current_fb_group['group_name']);
					var group_checkbox = $("<input>").attr({
						"type" : "checkbox", 
						"id" : current_fb_group['group_id'], 
						"class" : "current_fb_groups",
						"checked" : true
					});
					
					fb_group.append(group_checkbox);
					fb_group.append(group_name);
					
					current_fb_groups.append(fb_group);
					
					if(!current_user['settings']['facebook']['groups']){
						current_user['settings']['facebook']['groups'] = {};
					}
					
					current_user['settings']['facebook']['groups'][current_fb_group['group_id']] = {
						"group_name" : current_fb_group['group_name']
					};
					
					if(!current_users[current_user.uid]['settings']['facebook']['groups']){
						current_users[current_user.uid]['settings']['facebook']['groups'] = {};
					}
					
					current_users[current_user.uid]['settings']['facebook']['groups'][current_fb_group['group_id']] = {
						"group_name" : current_fb_group['group_name'],
						"group_status" : 1
					};
					users.set('users', current_users);
					
					noty_success.text = 'Facebook Group Successfully Added!';
					noty(noty_success);
				}else{
					noty_err.text = 'The selected Facebook Group has already been added before!';
					noty(noty_err);
				}
		});
		
		$('.current_fb_pages').live('click', function(){
			//change status whether to post to the currently selected facebook page or not
			var page_id = $(this).attr('id');
			var page_status = Number(!!$(this).attr('checked'));
			current_user['settings']['facebook']['pages'][page_id]['page_status'] = page_status; 
			
			current_users[current_user.uid]['settings']['facebook']['pages'][page_id]['page_status'] = page_status;
			users.set('users', current_users);
		});
		
		$('.current_fb_groups').live('click', function(){
			//change status whether to post to the currently selected facebook group or not
			var group_id = $(this).attr('id');
			var group_status = Number(!!$(this).attr('checked'));
			current_user['settings']['facebook']['groups'][group_id]['group_status'] = group_status; 
			
			current_users[current_user.uid]['settings']['facebook']['groups'][group_id]['group_status'] = group_status;
			users.set('users', current_users);
		});
		
		var fb_post = function(post_contents){
	
			//post to current users wall
			if(current_user['settings']['facebook']['status']){
				FB.api('/me/feed', 'post', post_contents, 
					function(response){
						if(!response || response.error){
							noty_err.text = 'Post to facebook profile was unsuccessful!';
							noty(noty_err);
						}
					}
				);
			}
			
			//post to pages checked by current user
			var current_fb_pages = current_user['settings']['facebook']['pages'];
			for(var page_id in current_fb_pages){
				var page_name = current_fb_pages[page_id]['page_name'];
				var page_status = current_fb_pages[page_id]['page_status'];
				
				
				
				if(!!page_status){
					FB.api('/' + page_id, {fields: 'access_token'}, function(data){
						if(data['access_token']){
							
							post_contents.access_token = data['access_token'];
							
							FB.api('/' + page_id + '/feed',
								'post',
								post_contents,
								function(response){
									if(!response || response.error){
										noty_err.text = 'Post to ' + page_name + ' was unsuccessfull!';
										noty(noty_err);
									}
								}
							);
						}
					});
				}
			}
		};
		
		
		$('#multi_post').click(function(){
			var status = Number(!!$(this).attr('checked'));
			current_user['settings']['multipost'] = status;
			
			current_users[current_user.uid]['settings']['multipost'] = status;
			users.set('users', current_users);
		});
		
		$('#status').keydown(function(){
			var current_length = $(this).val().length;
			var char_limit = 140;
			
			var remaining_char = char_limit - current_length;
			$('#char_limit').text(remaining_char);
			
			if(remaining_char <= 10){
				$('#char_limit').css('color', 'red');
			}else{
				$('#char_limit').css('color', 'black');
			}
		});
		
		var get_link = function(post){
			var url = '';
			var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
			var regex = new RegExp(expression);
			
			if(post.match(regex)){
				url = regex.exec(post)[0]; 
			}
			return url;
		};
		
	</script>
</html>
 