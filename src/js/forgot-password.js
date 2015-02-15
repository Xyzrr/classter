var forgotPassword = (function() {
	var conf = {
		sendEmailButton: $("#send-email-button"),
		emailField: $("#email-field"),
		passwordField: $("#password-field"),
		confirmPasswordField: $("#confirm-password-field"),
		resetPasswordButton: $("#reset-password-button"),
		userIDField: $("#user-id-field"),
		loginButton: $("#login-button")
	},
	init = function() {
		conf.sendEmailButton.click(sendEmail).validify({
			position: "right"
		});
		conf.emailField.validify({
			position: "right"
		});
		conf.passwordField.validify({
			position: "right"
		});
		conf.confirmPasswordField.validify({
			position: "right"
		});
		conf.resetPasswordButton.click(resetPassword).validify({
			position: "right"
		});
	},
	sendEmail = function(e) {
		e.preventDefault();
		$.ajax({
			url: config.ajaxPath + "/sendResetPasswordEmail.php",
			method: "POST",
			data: {
				email: conf.emailField.val()
			},
			success: function(json) {
				$.clearErrors();
				var result = $.parseJSON(json);
				if(result.success) {
					window.location = "../check-email/?email=" + encodeURI(conf.emailField.val());
				} else {
					conf.emailField.validify(result.email);
				}
			},
			error: function() {
				conf.sendEmailButton.displayError();
			}
		});
	},
	resetPassword = function(e) {
		e.preventDefault();
		$.ajax({
			url: config.ajaxPath + "/resetPassword.php",
			method: "POST",
			data: {
				userID: conf.userIDField.val(),
				password: conf.passwordField.val(),
				confirmPassword: conf.confirmPasswordField.val()
			},
			success: function(json) {
				$.clearErrors();
				var result = $.parseJSON(json);
				if(result.success) {
					window.location = "../done/";
				} else {
					conf.passwordField.validify(result.password);
					conf.confirmPasswordField.validify(result.confirmPassword);
				}
			},
			error: function() {
				conf.resetPasswordButton.displayError();
			},
		});
	}
	;
	return {
		init: init
	};
}());

$(function() {
	forgotPassword.init();
});