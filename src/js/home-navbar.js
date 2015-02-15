var homeNavbar = (function() {
  var conf = {
    login: {
      toggle: $("#login-toggle"),
      button: $("#login-button"),
      form: $("#login-form"),
      email: $("#login-email"),
      password: $("#login-password"),
    }
  },
  init = function() {
    conf.login.email.validify({
      position: "top"
    });
    conf.login.password.validify({
      position: "top"
    });

    // Setup drop down menu
    $('.dropdown-toggle').dropdown();
   
    // Fix input element click problem
    $('.dropdown input, .dropdown label').click(function(e) {
      e.stopPropagation();
    });

    conf.login.button.click(login);

    conf.login.toggle.click(function() {
        conf.login.email.focus();
    });

  },
  login = function(e) {
    e.preventDefault();
    window.alert("lol");
    $.ajax({
      method: "POST",
      url: conf.paths.login,
      data: conf.login.form.serialize(),
      success: function(json) {
        var result = $.parseJSON(json);
        if(result.success) {
          window.location = config.home + "/dashboard/";
        } else {
          $.clearErrors();
          conf.login.email.validify(result.email);
          conf.login.password.validify(result.password);
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