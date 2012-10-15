<script src="libs/jqueryui/js/jquery-1.8.2.min.js"></script>
<script src="libs/noty/js/jquery.noty.js"></script>
<script src="libs/storejs/source/store.js"></script>
<script src="libs/keymaster.js"></script>

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

	var ajaxLoad = function(){

		$('body').addClass('blurout');
		$('#ajaxloader').show();
	};

	var ajaxDone = function(){

		$('body').removeClass('blurout');
		$('#ajaxloader').hide();
	};

	//keyboard shortcuts
	key('s', function(){ //settings
		$('#settings_modal').reveal();
	});

	key('u', function(){ //upload file
		$('#upload').click();
	});

	key('f', function(){ //facebook settings
		$('#facebook_modal').reveal();
	});

	key('t', function(){ //tumblr settings
		$('#tumblr_modal').reveal();
	});

	key('a', function(){ //facebook pages
		$('#facebook_modal').reveal();
		$('a[href=#pages]').click();
		window.location.hash = 'pages';
	});

	key('b', function(){ //facebook groups
		$('#facebook_modal').reveal();
		$('a[href=#groups]').click();
		window.location.hash = 'groups';
	});

	key('c', function(){ //facebook lists
		$('#facebook_modal').reveal();
		$('a[href=#list]').click();
		window.location.hash = 'list';
	});

	key('l', function(){ //logout
		$('#logout').click();
	});
</script>