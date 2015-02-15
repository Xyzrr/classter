//The schedule page displays the schedule and includes a variety of buttons for configuration.
(function($) {
    'use strict';
    $.fn.schedule = function() {
        var sched = $(this),
            conf = {
                paths: {
                    getSchedule: config.ajaxPath + "/getSchedule.php",
                    getClassSuggestions: config.ajaxPath +
                        "/getClassSuggestions.php",
                    getCourses: config.ajaxPath + "/getCourses.php",
                    setCourseLevel: config.ajaxPath + "/setCourseLevel.php"
                },
                joinClassModal: $("#join-class"),
                teacherSearchField: $("#teacher-search"),
                setCourseModal: $("#set-course"),
                courseSearchField: $("#course-search"),
                teacherSearchResults: $("#teacher-search-results"),
                courseSearchResults: $("#course-search-results")
            },
            getSchedule = function() {
                $.ajax({
                    method: "GET",
                    url: conf.paths.getSchedule,
                    data: {
                        targetID: sched.data("id")
                    },
                    success: function(result) {
                        sched.html(result);
                        var footable = $(".footable");
                        footable.footable();
                        initializeTable();
                        footable.on("footable_row_expanded",
                            initializeTable);
                        footable.on("footable_breakpoint",
                            initializeTable);
                        $("select").selectpicker();
                        $("select.course-level-selector").change(setCourseLevel);
                    }
                });
            },
            initializeTable = function() {
                $(".set-period, .change-teacher").click(changeTeacher);
                $(".change-course, .set-course").click(changeCourse);

                var cell = $(".footable-row-detail .change-room");
                cell.each(function() {
                    var that = $(this);
                    var period = $(this).data("period");
                    if(!$(this).data("editable")) {
                        $(this).editable(function(div){

                            var room = that.find("#room").val();
                            that.find("#room-read").text(room);
                            changeRoom(div, period, room);

                        }).data("editable", true);
                    }
                });
            },
            changeTeacher = function() {
                var period = $(this).val();
                conf.joinClassModal.modal("show");
                getClassSuggestions(period, "");
                conf.teacherSearchField.attr("placeholder",
                    "Your period " + period + " teacher's name");
                setTimeout(function() {
                    conf.teacherSearchField.focus();
                }, 500);
                var timeout;
                conf.teacherSearchField.keyup(function() {
                    conf.teacherSearchResults.fadeOut(100);
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        getClassSuggestions(period, conf.teacherSearchField.val());
                    }, 100);
                });
            },
            changeCourse = function() {
                var period = $(this).val();
                conf.setCourseModal.modal("show");
                getCourseSuggestions(period, "");
                conf.courseSearchField.attr("placeholder",
                    "Your period " + period + " course name");
                setTimeout(function() {
                    conf.courseSearchField.focus();
                }, 500);
                conf.courseSearchField.keyup(function() {
                    getCourseSuggestions(period, conf.courseSearchField.val());
                });
            },
            getClassSuggestions = function(period, query) {
                if(typeof(conf.currentClassAjaxRequest) !== "undefined") {
                    conf.currentClassAjaxRequest.abort();
                }
                conf.currentClassAjaxRequest = $.ajax({
                    method: "GET",
                    cache: false,
                    url: conf.paths.getClassSuggestions,
                    data: {
                        query: query,
                        period: period
                    },
                    success: function(result) {
                        conf.teacherSearchResults.html(result);
                        conf.teacherSearchResults.fadeIn(100);
                        var thumbnails = $(".thumbnail-wrapper");
                        thumbnails.click(function() {
                            conf.teacherSearchField.val("");
                            leaveClass(period);
                            joinClass(period, $(
                                this).data(
                                "teacher"));
                        });
                    }
                });
            },
            getCourseSuggestions = function(period, query) {
                    if(typeof(conf.currentCourseAjaxRequest) !== "undefined") {
                        conf.currentCourseAjaxRequest.abort();
                    }
                    conf.currentCourseAjaxRequest = $.ajax({
                    method: "GET",
                    cache: false,
                    url: conf.paths.getCourses,
                    data: {
                        query: query
                    },
                    success: function(result) {
                        conf.courseSearchResults.html(
                            result);
                        $(".course-suggestion").click(
                            function() {
                                conf.courseSearchField.val("");
                                setCourse(period, $(
                                    this).data(
                                    "course"));
                            });
                    }
                });
            },
            joinClass = function(period, teacherUserID) {
                conf.joinClassModal.modal("hide");
                $.ajax({
                    method: "POST",
                    url: config.ajaxPath + "/joinClass.php",
                    data: {
                        period: period,
                        teacherUserID: teacherUserID
                    },
                    success: function() {
                        getSchedule();
                    }
                });
            },
            leaveClass = function(period) {
                $.ajax({
                    method: "POST",
                    url: config.ajaxPath + "/leaveClass.php",
                    data: {
                        period: period
                    }
                });
            },
            setCourse = function(period, courseID) {
                conf.setCourseModal.modal("hide");
                $.ajax({
                    method: "POST",
                    url: config.ajaxPath + "/setCourse.php",
                    data: {
                        period: period,
                        courseID: courseID
                    },
                    success: function() {
                        getSchedule();
                    }
                });
            },
            setCourseLevel = function() {
                var period = $(this).data("period");
                $.ajax({
                    method: "GET",
                    url: conf.paths.setCourseLevel,
                    data: {
                        period: period,
                        courseLevelID: $(this).val()
                    }
                });
            },
            changeRoom = function(div, period, room) {
                $.ajax({
                    method: "POST",
                    url: config.ajaxPath + "/changeRoom.php",
                    data: {
                        period: period,
                        room: room
                    },
                    success: function() {
                        div.editable("toggle");
                    }
                });
            }
            ;
        getSchedule();
    };
}(window.jQuery));