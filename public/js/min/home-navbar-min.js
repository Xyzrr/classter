var homeNavbar=function(){var r={paths:{login:config.ajaxPath+"/login.php",register:config.ajaxPath+"/register.php"},login:{button:$("#login-button"),form:$("#login-form"),email:$("#login-email"),password:$("#login-password")},register:{button:$("#register-button"),form:$("#register-form"),firstName:$("#register-first-name"),lastName:$("#register-last-name"),email:$("#register-email"),password:$("#register-password")}},i=function(){r.login.email.validify({position:"left"}),r.login.password.validify({position:"left"}),r.register.firstName.validify({position:"right"}),r.register.lastName.validify({position:"right"}),r.register.email.validify({position:"right"}),r.register.password.validify({position:"right"}),$(".dropdown-toggle").dropdown(),$(".dropdown input, .dropdown label").click(function(r){r.stopPropagation()}),r.login.button.click(o),r.register.button.click(e)},o=function(){$.ajax({method:"POST",url:r.paths.login,data:r.login.form.serialize(),success:function(i){var o=$.parseJSON(i);o.success?window.location="profile/":($.clearErrors(),r.login.email.checkError(o.email),r.login.password.checkError(o.password))}})},e=function(){$.ajax({method:"POST",url:r.paths.register,data:r.register.form.serialize(),success:function(i){var o=$.parseJSON(i);o.success?window.location="profile/":($.clearErrors(),r.register.firstName.checkError(o.firstName),r.register.lastName.checkError(o.lastName),r.register.email.checkError(o.email),r.register.password.checkError(o.password))}})};return{init:i}}();$(function(){homeNavbar.init()});