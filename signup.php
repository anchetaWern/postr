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
				  
				  
				  <a href="#" id="sign_up" class="success button">Sign Up</a>
				  <a href="index.php">Login</a>
				</form> 
			</div><!--/.form_container-->
		</div><!--/.container-->
	</body>
<?php
include('includes/footer.php');
?>	
	<script>
	var networks = new Store('networks');
	var users = new Store('users');
	var current_users = {};
	
	var current_user = {
		settings : {}
	};
	
	if(!networks.get('networks')){
		//set networks if it doesn't exists yet
		networks.set('networks', ['facebook', 'twitter']);
	}
	
	if(!users.get('users')){
		//set users if it doesn't exists yet
		users.set('users', {});
	}else{
		current_users = users.get('users');
	}
	
	
	
	$('#sign_up').click(function(e){
		e.preventDefault();
		
		var user_info = {
			email : $.trim($('#email').val()),
			pword : $.trim($('#pword').val())
		};

		ajaxLoad();
		
		$.post(
			'actions.php', 
			{'action' : 'sign_up', 'email' : user_info.email, 'pword' : user_info.pword}, 
			function(data){
				var uid = data;
				if(data > 0){
					
					noty_success.text = 'Account was successfully created!';
					noty(noty_success);
					
					var current_networks = networks.get('networks');
					for(var x in current_networks){
						var current_network = current_networks[x];
						current_user['settings'][current_network] = {status : 0};
					}
					
					current_users[uid] = current_user;
					users.set('users', current_users);

					ajaxDone();
				}else{
					noty_err.text = 'An Error Occured While Creating Your Account, Please Try Again.';
					noty(noty_err);
					ajaxDone();
				}
				
				$('#email, #pword').val('');
			}
		);
	});
	</script>
</html>