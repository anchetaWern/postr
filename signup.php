<!doctype html>
<html>
	<head>
		<title>Postr</title>
		<link rel="stylesheet" href="libs/foundation/stylesheets/foundation.css"/>
		<link rel="stylesheet" href="libs/noty/css/jquery.noty.css"/>
		<link rel="stylesheet" href="libs/noty/css/noty_theme_default.css"/>
		<link rel="stylesheet" href="css/main.css"/>
		
		<link rel="postr_icon" href="img/postr.ico">

	</head>
	<body>
		<div class="container">
			<div class="app_title">
				<img src="img/postr.png"/>
				<h4>Postr</h4>
			</div>
			<div class="form_container">
				<form>
				  <label>Email</label>
				  <input type="text" name="email" id="email" autofocus/>
				  
				  <label>Password</label>
				  <input type="password" name="pword" id="pword"/>
				  
				  
				  <a href="#" id="sign_up" class="success button">Sign Up</a>
				  <a href="index.php">Login</a>
				</form> 
			</div><!--/.form_container-->
		</div><!--/.container-->
	</body>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="libs/noty/js/noty/jquery.noty.js"></script>
	<script src="libs/noty/js/jquery.noty.js"></script>
	
	
	<script>
	var noty_success = {
		"text":"Operation was successfully completed!",
		"layout":"top",
		"type":"success",
		"textAlign":"center",
		"easing":"swing",
		"animateOpen":{"height":"toggle"},
		"animateClose":{"height":"toggle"},
		"speed":500,
		"timeout":5000,
		"closable":true,
		"closeOnSelfClick":true
	}
	
	var noty_err = {
		"text":"An error occured, please try again",
		"layout":"top",
		"type":"error",
		"textAlign":"center",
		"easing":"swing",
		"animateOpen":{"height":"toggle"},
		"animateClose":{"height":"toggle"},
		"speed":500,
		"timeout":5000,
		"closable":true,
		"closeOnSelfClick":true
	}
	
	$('#sign_up').click(function(){
		var user_info = {
			email : $.trim($('#email').val()),
			pword : $.trim($('#pword').val())
		};
		
		$.post(
			'actions/actions.php', 
			{'action' : 'sign_up', 'email' : user_info.email, 'pword' : user_info.pword}, 
			function(data){
				if(data == 1){
					noty_success.text = 'Account was successfully created!';
					noty(noty_success);
				}else{
					noty_success.text = 'An Error Occured While Creating Your Account, Please Try Again.';
					noty(noty_err);
				}
				
				$('#email, #pword').val('');
			}
		);
	});
	</script>
</html>
 