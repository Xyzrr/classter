var menuToggle = (function() {

  var conf = {
    menuButton: $("#menu-toggle"),
    wrapper: $("#wrapper"),
    sidebar: $("#sidebar-wrapper"),
    toggledClass: "toggled"
  },

  init = function() {
    conf.menuButton.on({
      "click": toggleMenu,
      "mouseenter": showMenu
    });
    conf.sidebar.mouseleave(hideMenu);
  },

  toggleMenu = function(e) {
    e.preventDefault();
    conf.wrapper.toggleClass(conf.toggledClass);
  },

  showMenu = function(e) {
    e.preventDefault();
    conf.wrapper.addClass(conf.toggledClass);
  },

  hideMenu = function(e) {
    e.preventDefault();
    conf.wrapper.removeClass(conf.toggledClass);
  };

  return {
    init: init
  };

}());

var schedule = (function() {
  var conf = {
    getSchedulePath: config.ajaxPath + "/getSchedule.php",
    schedule: $("#schedule"),
    table: $(".footable"),
    setPeriodButton: $(".set-period"),
    changeTeacherButton: $(".change-teacher"),
    joinClassModal: $("#join-class"),
    teacherSearchField: $("#teacher-search"),
    changeCourseButton: $(".change-course"),
    setCourseModal: $("#set-course"),
    courseSearchField: $("#course-search")
  },

  getSchedule = function() {
    $.ajax({
      method: "GET",
      url: getSchedulePath,
      success: function(result) {

        schedule.html(result);

        table.footable();

        setPeriodButton.click(changeTeacher);
        changeTeacherButton.click(changeTeacher);

        changeCourseButton.click(changeCourse);
      }
    });
  },

  changeTeacher = function() {
    var period = $(this).val();

    joinClassModal.modal("show");
    getClassSuggestions(period);
    setTimeout(function() {
      $("#teacher-search").focus();
    },
    500
    );

    $("#teacher-search").keyup(function() {
      getClassSuggestions(period);
    });
  },

  changeCourse = function() {
    var period = $(this).val();

    $("#join-class").modal("show");
    getClassSuggestions(period);
    setTimeout(function() {
      $("#teacher-search").focus();
    },
    500
    );

    $("#teacher-search").keyup(function() {
      getClassSuggestions(period);
    });
  }
  ;
}());

function getClassSuggestions(period) {
  $.ajax({
    method: "GET",
    cache: false,
    url: config.ajaxPath + "/getClassSuggestions.php",
    data: {
      query: $("#teacher-search").val(),
      period: period
    },
    success: function(result) {
      $("#teacher-search-results").html(result);

      $(".join-class").click(function() {
        leaveClass(period);
        joinClass($(this).data("class"));
      });

      $(".create-class").click(function() {
        leaveClass(period);
        createClass(period, $(this).data("teacher"));
      });
    }
  });
}

function getCourseSuggestions(period) {
  $.ajax({
    method: "GET",
    cache: false,
    url: config.ajaxPath + "/getCourses.php",
    data: {
      query: $("#course-search").val(),
      period: period
    },
    success: function(result) {
      $("#course-search-results").html(result);

      $(".course-suggestion").click(function() {
        setCourse($(this).data("period"), $(this).data("class"));
      });
    }
  });
}

function joinClass(classID) {
  $("#join-class").modal("hide");

  $.ajax({
    method: "POST",
    url: config.ajaxPath + "/joinClass.php",
    data: {
      classID: classID
    },
    success: function(){getSchedule();}
  });
}

function createClass(period, teacherID) {
  $("#join-class").modal("hide");

  $.ajax({
    method: "POST",
    url: config.ajaxPath + "/createClass.php",
    data: {
      period: period,
      teacherID: teacherID
    },
    success: function(result){
      getSchedule();
    }
  });
}

function leaveClass(period) {
  $.ajax({
    method: "POST",
    url: config.ajaxPath + "/leaveClass.php",
    data: {
      period: period
    }
  });
}

function setCourse(period, courseID) {
    $("#set-course").modal("hide");

  $.ajax({
    method: "POST",
    url: config.ajaxPath + "/setCourse.php",
    data: {
      period: period,
      courseID: courseID
    },
    success: function(result){
      getSchedule();
    }
  });
}

function resizeiframes() {
  $(".page-frame, .mobile-iframe").height(window.innerHeight - 97);
}

function addhttp(url) {
  if (!url.match(/^[a-zA-Z]+:\/\//)) {
      url = 'http://' + url;
  }
  return url;
}

function setHomeworkURL(url, classID) {
    $.ajax({
    method: "POST",
    url: config.ajaxPath + "/setHomeworkURL.php",
    data: {
      url: url,
      classID: classID
    },
    success: function(result){
      //Success indicator
    }
  });
}

$(function() {
  menuToggle.init();

  if($("#schedule").length)
    getSchedule();

  resizeiframes();
  $(window).resize(function() {
    resizeiframes();
  });

  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    $(".page").addClass("mobile-iframe");
  }

  $(".page-tab").click(function(){
    var url = $(this).data("url");

    $(".page-frame").attr("src", url);
  });

  $(".settings").click(function() {
    $(".settings-wrapper").slideToggle();
  });

  $("#url-submit").click(function(e) {
    e.preventDefault();
    var url = $("#url-input").val();
    url = addhttp(url);

    $(".page-frame").attr("src", url);
  });

  var loader = "<i class='fa fa-spinner fa-spin'></i>";
  var finished = "<i class='fa fa-check'></i>";

  $("#save-url-button").click(function() {
    if($("#url-input").val() === "") {
      $("#url-input").attr("placeholder", "Please enter a URL.");
    } else {
      var newurl = addhttp($("#url-input").val());
      $("li.active>.page-tab").data("url", newurl);
      $(".page-frame").attr("src", newurl);
      setHomeworkURL(
        newurl,
        $("li.active>.page-tab").data("class")
        );
    }
  });

  $("#home-button").click(function() {
    var homeURL = $("li.active>.page-tab").data("home");
    $(".page-frame").attr("src", homeURL);
  });

  $("#homework-button").click(function() {
    var homeworkURL = $("li.active>.page-tab").data("url");
    $(".page-frame").attr("src", homeworkURL);
  });
});