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
				<h2>Postr</h2>
			</div>
			
			<div class="form_container">
				<form class="custom">
					<label for="status">What's Up?</label>
					<textarea name="status" id="status" style="height:100px;">
					</textarea>
					
					<a href="#" id="post_status" class="success button">Post</a>
					<a href="#" id="settings">Settings</a>
					
				</form> 
			</div><!--/.form_container-->
			
			
		</div><!--/.container-->
		
		<div id="settings_modal" class="reveal-modal medium">
			<h3>Settings</h3>
			<span>Where to Post?</span>
			<p>
				<form class="custom" id="settings_form">
					<p>
						<label data-for="facebook">
							<input type="checkbox" id="facebook">
							<span class="custom checkbox"></span>
							<a href="#" class="network_settings">Facebook</a>
						</label>
						
					</p>
					<p>
						<label data-for="twitter">
							<input type="checkbox" id="twitter">
							<span class="custom checkbox"></span>
							<a href="#" class="network_settings">Twitter</a>
						</label>
					</p>
					<p>
						<label data-for="gplus">
							<input type="checkbox" id="gplus" >
							<span class="custom checkbox"></span>
							<a href="#" class="network_settings">Google+</a>
						</label>
					</p>
					<p>
						<label data-for="linked_in">
							<input type="checkbox" id="linked_in">
							<span class="custom checkbox"></span>
							<a href="#" class="network_settings">LinkedIn</a>
						</label>
					</p>
					
				</form>
			</p>
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#settings_modal-->
		
		<div id="facebook_modal" class="reveal-modal medium">
			<h3>Facebook Settings</h3>
			<p>
				<label for="fb_pages">Pages</label>
				<input type="text" id="fb_pages" list="fb_pages_list" autocomplete="off"/>
				<datalist id="fb_pages_list">
				
				</datalist>
			</p>
			<a class="close-reveal-modal">&#215;</a>
		</div><!--/#facebook_modal-->
		
		<div id="fb-root"></div>
	</body>
	
	
	
<?php
include('includes/footer.php');
?>	
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.reveal.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.forms.js"></script>
	
	<script>
		var users = new Store("users");
		var current_users = {};
		var current_user = {
		};
		
		$(document).foundationCustomForms();
		
		$('#status').val("");
		
		
		$.post(
			'actions/actions.php',
			{'action' : 'get_uid'},
			function(data){
				current_user.uid = data;
				current_user.settings = users.get('users')[current_user.uid]['settings'];
			}
		);
		
		if(!users.get('users')){
			//set users if it doesn't exists yet
			users.set('users', {});
		}else{
			current_users = users.get('users');
		}
		
		
		/*
		$.post(
			'actions/actions.php', 
			{'action' : 'load_set'},
			function(data){
				var settings = JSON.parse(data);
				var set_length = settings.length;
				for(var x in settings){
					
					if(settings[x]['status'] != 0){
						$($('#settings_form span')[x]).addClass('checked');
						var network = $($('#settings_form span')[x]).parents('label').data('for');
						
						switch(network){
							case 'facebook':
								fb_login();
							break;
						}
					}
				}
			}
		);
		*/
		
		for(var x in current_user.settings){
			var network = x;
			var network_status = current_user.settings[x]['status'];
			if(network_status != 0){
				$($('#settings_form span')[x]).addClass('checked');
				
				switch(network){
					case 'facebook':
						fb_login();
					break;
				}
			}
		}
		
		
		$('.network_settings').live('click', function(e){
			e.preventDefault();
			$('#facebook_modal').reveal();
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
			fb_post();
		});
		
		$('#settings_form span').click(function(){
			
			var network = $(this).siblings('input').attr('id');
			var status = Number(!$(this).hasClass('checked'));
			
			current_user['settings'][network]['status'] = status;
			current_users[current_user.uid]['settings'][network]['status'] = status;
			users.set(current_users);
			
			/*
			$.post(
				'actions/actions.php', 
				{'action' : 'set', 'sid' : sid, 'status' : status}
			);
			*/
			
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
							console.log(data);
							var user_pages = data[1]['fql_result_set'];
							for(var x in user_pages){
								var page_obj = user_pages[x];
								
								var page_id = page_obj['page_id'];
								var page_name = page_obj['name'];
								var page_description = page_obj['description'];
								var page_pic = page_obj['description']
								var fragment = document.createDocumentFragment();
								
								var page_item = $("<option>")
												.attr("value", page_name)
												.text(page_name)
												.data(
													{
													'id' : page_id,
													'pic' : page_pic,
													'description' : page_description
													}
												);
								
								page_item.appendTo(fragment);
								
							}
							
							$('#fb_pages_list').append(fragment);
						}
					);
				}, 
				{scope: 'user_about_me,email,read_friendlists,publish_stream,manage_pages'}
			);
		
		};
		
		var fb_post = function(){
			var post_contents = {
				message : 'Testing message with images and links',
				name : 'test test',
				link : 'http://google.com',
				description : 'test post to facebook page'
			};

			FB.api('/217828178231935/feed', 'post', post_contents, 
				function(response){
					if(!response || response.error){
						noty_err.text = 'Facebook Post Unsuccessful';
						noty(noty_err);
					}else{
						
					}
				}
			);
		};
		
		
	</script>
</html>
 