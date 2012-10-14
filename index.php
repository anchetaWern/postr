<?php
include('includes/header.php');
?>
	<body>
		<div class="container">
			<img src="assets/ajax-loader.gif" id="ajaxloader" style="display:none;"/>
			<div class="app_title">
				<img src="img/postr.png"/>
				<h2>Postr</h2>
			</div>
			<div class="form_container">
				<form>
				  <label for="email">Email</label>
				  <input type="text" name="email" id="email" autofocus/>
				  
				  <label for="pword">Password</label>
				  <input type="password" name="pword" id="pword"/>
				  
				 
				  <a href="#" id="login" class="success button">Login</a>
				  <a href="signup.php">Signup</a>

				  <label for="remember">Remember Me
				  <input type="checkbox" name="remember" id="remember"/>
				  </label>
				</form> 
			</div><!--/.form_container-->
		</div><!--/.container-->
	</body>
<?php
include('includes/footer.php');
?>		

	<script>
	var buildFbSettings = function(fbID, fb_user, fb_status, fb_pic){
		$.post(
			'actions.php',
			{
				'action' : 'build_settings',
				'fb_id' : fbID,
				'fb_user' : fb_user,
				'fb_status' : fb_status,
				'fb_pic' : fb_pic
			}
		);
	};

	$('#login').click(function(e){
		e.preventDefault();
		
		var user_info = {
			email : $.trim($('#email').val()),
			pword : $.trim($('#pword').val()),
			remember : $('#remember').attr('checked') || 'off'
		};
		
		ajaxLoad();

		$.post(
			'actions.php', 
			{
				'action' : 'login', 'email' : user_info.email, 
				'pword' : user_info.pword, 
				'remember' : user_info.remember
			},
			function(data){
				if(parseInt(data) > 0){

					FB.getLoginStatus(function(response){
		 				if(response.status === "connected"){

		 					var fbuser_id = FB.getUserID();

		 					$.ajax({
								type : 'post', 
								url : 'actions.php',
								async : false,
								data : {"action" : "verify_fbuser", "fbuser_id" : fbuser_id}
							}).done(function(oauth_count){
								  if(parseInt(oauth_count)){ //facebook user is already registered with the app

										FB.api({
											method : 'fql.query',
											query : 'SELECT name, pic_small FROM user WHERE uid=me()'
										}, function(data){
												var fb_user = data[0]['name'];
												var fb_status = "verified_user";
												var fb_pic = data[0]['pic_small'];

												buildFbSettings(fbuser_id, fb_user, fb_status, fb_pic);

										});	
								  }
							});
		 				
		 				}else if(response.status === 'not_authorized'){//unknown user
		 					buildFbSettings("", "", "unknown_user", "img/default.png");
		 				}else{//not logged in
		 					buildFbSettings("", "", "no_user", "img/default.png");
		 				}
		   		});

					noty_success.text = 'Login Successfull!';
					noty(noty_success);
					
					ajaxDone();
					setTimeout(function(){
						window.location.replace('postr.php');
					}, 1000);
					
				}else{
					noty_err.text = 'Incorrect User Credentials!';
					noty(noty_err);

					ajaxDone();
				}
			}
		);
		
	});

  (function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
 	}(document));

  window.fbAsyncInit = function(){
    FB.init({
      appId      : '355248497890497',
      status     : true, // check login status
      cookie     : true // enable cookies to allow the server to access the session
    });

  };
	</script>
</html>
 