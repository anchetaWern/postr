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
			<span>Where to Post</span>
			<p>
				<form class="custom" id="settings_form">
					<p>
						<label for="facebook">
							<input type="checkbox" id="facebook">
							<span class="custom checkbox" data-sid="1"></span>
							Facebook
						</label>
					</p>
					<p>
						<label for="twitter">
							<input type="checkbox" id="twitter">
							<span class="custom checkbox" data-sid="2"></span>
							Twitter
						</label>
					</p>
					<p>
						<label for="gplus">
							<input type="checkbox" id="gplus" >
							<span class="custom checkbox" data-sid="3"></span>
							Google+
						</label>
					</p>
					<p>
						<label for="linked_in">
							<input type="checkbox" id="linked_in">
							<span class="custom checkbox" data-sid="4"></span>
							LinkedIn
						</label>
					</p>
				</form>
			</p>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	</body>
	
	
	
<?php
include('includes/footer.php');
?>	
	<script src="libs/foundation/javascripts/jquery.foundation.reveal.js"></script>
	<script src="libs/foundation/javascripts/jquery.foundation.forms.js"></script>
	<script>
		$(document).foundationCustomForms();
		
		$.post(
			'actions/actions.php', 
			{'action' : 'load_set'},
			function(data){
				var settings = JSON.parse(data);
				var set_length = settings.length;
				for(var x in settings){
					
					if(settings[x]['status'] != 0){
						$($('#settings_form span')[x]).addClass('checked');
					}
				}
			}
		);
		
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
		
		$('#post_status').click(function(){
		
		});
		
		$('#settings_form span').click(function(){
			
			var sid = $(this).data('sid');
			var status = Number(!$(this).hasClass('checked'));
			
			$.post(
				'actions/actions.php', 
				{'action' : 'set', 'sid' : sid, 'status' : status}
			);
			
		});
	</script>
</html>
 