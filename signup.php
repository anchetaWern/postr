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
	$('#sign_up').click(function(e){
		e.preventDefault();
		
		var user_info = {
			email : $.trim($('#email').val()),
			pword : $.trim($('#pword').val())
		};
		
		$.post(
			'actions/actions.php', 
			{'action' : 'sign_up', 'email' : user_info.email, 'pword' : user_info.pword}, 
			function(data){
				
				if(data > 0){
					noty_success.text = 'Account was successfully created!';
					noty(noty_success);
				}else{
					noty_err.text = 'An Error Occured While Creating Your Account, Please Try Again.';
					noty(noty_err);
				}
				
				$('#email, #pword').val('');
			}
		);
	});
	</script>
</html>