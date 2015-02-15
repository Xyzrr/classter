var homeNavbar = (function() {
  var conf = {
    paths: {
      login: config.ajaxPath + "/login.php",
      register: config.ajaxPath + "/register.php"
    },
    login: {
      button: $("#login-button"),
      form: $("#login-form"),
      email: $("#login-email"),
      password: $("#login-password"),
    },
    register: {
      button: $("#register-button"),
      form: $("#register-form"),
      firstName: $("#register-first-name"),
      lastName: $("#register-last-name"),
      email: $("#register-email"),
      password: $("#register-password")
    }
  },
  init = function() {
    conf.login.email.validify({
      position: "left"
    });
    conf.login.password.validify({
      position: "left"
    });
    conf.register.firstName.validify({
      position: "right"
    });
    conf.register.lastName.validify({
      position: "right"
    });
    conf.register.email.validify({
      position: "right"
    });
    conf.register.password.validify({
      position: "right"
    });

    // Setup drop down menu
    $('.dropdown-toggle').dropdown();
   
    // Fix input element click problem
    $('.dropdown input, .dropdown label').click(function(e) {
      e.stopPropagation();
    });

    conf.login.button.click(login);
    conf.register.button.click(register);
  },
  login = function() {
    $.ajax({
      method: "POST",
      url: conf.paths.login,
      data: conf.login.form.serialize(),
      success: function(json) {
        var result = $.parseJSON(json);
        if(result.success) {
          window.location = "profile/";
        } else {
          $.clearErrors();
          conf.login.email.checkError(result.email);
          conf.login.password.checkError(result.password);
        }
      }
    });
  },
  register = function() {
    $.ajax({
      method: "POST",
      url: conf.paths.register,
      data: conf.register.form.serialize(),
      success: function(json) {
        var result = $.parseJSON(json);
        if(result.success) {
          window.location = "profile/";
        } else {
          $.clearErrors();
          conf.register.firstName.checkError(result.firstName);
          conf.register.lastName.checkError(result.lastName);
          conf.register.email.checkError(result.email);
          conf.register.password.checkError(result.password);
        }
      }
    });
  }
  ;
  return {
    init: init
  };
}());

$(function() {
  homeNavbar.init();
});