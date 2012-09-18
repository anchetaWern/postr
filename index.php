<?php
include('includes/header.php');
?>
	<body>
		<div class="container">
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
				</form> 
			</div><!--/.form_container-->
		</div><!--/.container-->
	</body>
<?php
include('includes/footer.php');
?>		
	<script>
	$('#login').click(function(e){
		e.preventDefault();
		
		var user_info = {
			email : $.trim($('#email').val()),
			pword : $.trim($('#pword').val())
		};
		
		$.post(
			'actions/actions.php', 
			{'action' : 'login', 'email' : user_info.email, 'pword' : user_info.pword},
			function(data){
				if(parseInt(data) > 0){
					noty_success.text = 'Login Successfull!';
					noty(noty_success);
					
					setTimeout(function(){
						window.location.replace('postr.php');
					}, 1000);
					
				}
			}
		);
	});
	</script>
</html>
 