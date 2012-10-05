<script src="libs/jqueryui/js/jquery-1.8.2.min.js"></script>
<script src="libs/noty/js/jquery.noty.js"></script>
<script src="libs/storejs/source/store.js"></script>

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
</script>