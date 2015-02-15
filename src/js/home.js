var home = (function() {
  var conf = {
      paths: {
        register: config.ajaxPath + "/register.php"
      },
      register: {
        toggle: $("#register-toggle"),
        button: $("#register-button"),
        form: $("#register-form"),
        firstName: $("#register-first-name"),
        lastName: $("#register-last-name"),
        email: $("#register-email"),
        password: $("#register-password")
      },
      teacherRegister: {
        modal: $("#teacher-register-modal"),
        authModal: $("#sent-email-modal"),
        toggle: $("#teacher-register-toggle"),
        list: $("#teacher-list"),
        search: $("#teacher-search-field"),
        email: $("#teacher-email")
      }
    },
    init = function() {
      conf.register.firstName.validify({
        position: "top"
      });
      conf.register.lastName.validify({
        position: "top"
      });
      conf.register.email.validify({
        position: "top"
      });
      conf.register.password.validify({
        position: "top"
      });
      conf.register.button.click(register);
      conf.register.toggle.click(function() {
        setTimeout(function() {
          conf.register.firstName.focus();
        }, 600);
      });
      conf.teacherRegister.toggle.click(registerAsTeacher);
    },
    register = function(e) {
      e.preventDefault();
      $.ajax({
        method: "POST",
        url: conf.paths.register,
        data: conf.register.form.serialize(),
        success: function(json) {
          var result = $.parseJSON(json);
          if (result.success) {
            window.location = config.home + "/profile/";
          } else {
            $.clearErrors();
            conf.register.firstName.validify(result.firstName);
            conf.register.lastName.validify(result.lastName);
            conf.register.email.validify(result.email);
            conf.register.password.validify(result.password);
          }
        }
      });
    },
    registerAsTeacher = function() {
      getUnregisteredTeachers("");
      setTimeout(function() {
          conf.teacherRegister.search.focus();
      }, 500);
      var timeout;
      conf.teacherRegister.search.keyup(function() {
          conf.teacherRegister.list.fadeOut(100);
          clearTimeout(timeout);
          timeout = setTimeout(function() {
              getUnregisteredTeachers(conf.teacherRegister.search.val());
          }, 100);
      });
    },
    getUnregisteredTeachers = function(query) {
      $.ajax({
        method: "GET",
        cache: false,
        url: config.ajaxPath + "/getPeople.php",
        data: {
          query: query,
          count: 20,
          offset: 0,
          filter: "unregistered-teachers"
        },
        success: function(json) {
          var result = $.parseJSON(json);
          if(result.length > 0) {
              conf.teacherRegister.list.html("");
              for(var index in result) {
                  var wrapper = createPersonWrapper(result[index]);
                  conf.teacherRegister.list.append(wrapper);
              }
              $(".thumbnail-wrapper").click(sendAuthenticationEmail);
          } else {
              conf.teacherRegister.list.html("<div class='no-results'>" +
                  "<h1><i class='fa fa-search'></i></h1>" +
                  "<p>No results for " + conf.teacherRegister.search.val() + "</p>" +
              "</div>");
          }
          conf.teacherRegister.list.fadeIn(100);
        }
      });
    },
    createPersonWrapper = function(person) {
      return $("<div class='thumbnail-wrapper' data-id=" + person.userID + ">" +
                "<div class='image'>" +
                    "<img src='" + person.thumbnail + "'/>" +
                "</div>" +
                "<div class='info'>" +
                    "<span class='name'>" + person.name + "</span>" +
                    "<span class='role'>" + person.role + "</span>" +
                "</div>" +
            "</div>");
    },
    sendAuthenticationEmail = function() {
      conf.teacherRegister.modal.modal("hide");
      $.ajax({
        method: "POST",
        url: config.ajaxPath + "/teacherAuth.php",
        data: {
          userID: $(this).data("id")
        },
        success: function(json) {
          var result = $.parseJSON(json);
          if(result.success) {
            conf.teacherRegister.authModal.modal("show");
            conf.teacherRegister.email.text(result.email);
          }
        }
      });
    };
  return {
    init: init
  };
}());
$(function() {
  home.init();
});