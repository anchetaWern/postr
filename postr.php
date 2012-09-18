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
				<h4>Postr</h4>
			</div>
			
			<div class="form_container">
				<form>
					
				</form> 
			</div><!--/.form_container-->
		</div><!--/.container-->
	</body>
	
	
	
<?php
include('includes/footer.php');
?>	

	<script>
		$('#logout').click(function(){
			$.post(
				'actions/actions.php', 
				{'action' : 'logout'}, 
				function(){
					window.location.replace('index.php');
				}
			);
		});
	</script>
</html>
 