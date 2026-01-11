(function($) { 
	"use strict";
	
	// hide or show password
	$('#showpassword').click(function() {
		var type = $('#password').attr('type');
		if(type=='text') {
		  	$("#eyeicon").removeClass('fa fa-eye-slash').addClass('fa fa-eye');
		  	$('#password').attr('type','password');
		} else if(type=='password') {
		  	$("#eyeicon").removeClass('fa fa-eye').addClass('fa fa-eye-slash');
		  	$('#password').attr('type','text');
		}
	});

	// Generate Password
	$('#generate_password').click(function() {
		var password = generate_password();
		$('#password').val(password);
		$('#password').attr('type','text');
	});

	// Generate Password function
	function generate_password() {
		var possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
		var password = '';
		for (var i = 0; i <= 10; i++) {
	  		password += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return password;
	}

	$(document).ready(function() {
		$('#filterRoleID').change(function() {
			var url    = $(this).data('url');
			var roleID = $(this).val();
			window.location.href= url+'/'+roleID;
		});
	});
})(jQuery);