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
            },
            changeTeacher = function() {
                var period = $(this).val();
                conf.joinClassModal.modal("show");
                getClassSuggestions(period);
                conf.teacherSearchField.attr("placeholder",
                    "Your period " + period + " teacher's name");
                setTimeout(function() {
                    conf.teacherSearchField.focus();
                    conf.teacherSearchField.val("");
                }, 500);
                conf.teacherSearchField.keyup(function() {
                    getClassSuggestions(period);
                });
            },
            changeCourse = function() {
                var period = $(this).val();
                conf.setCourseModal.modal("show");
                getCourseSuggestions(period);
                conf.courseSearchField.attr("placeholder",
                    "Your period " + period + " course name");
                setTimeout(function() {
                    conf.courseSearchField.focus();
                    conf.courseSearchField.val("");
                }, 500);
                conf.courseSearchField.keyup(function() {
                    getCourseSuggestions(period);
                });
            },
            getClassSuggestions = function(period) {
                $.ajax({
                    method: "GET",
                    cache: false,
                    url: conf.paths.getClassSuggestions,
                    data: {
                        query: conf.teacherSearchField.val(),
                        period: period
                    },
                    success: function(result) {
                        conf.teacherSearchResults.html(result);
                        var thumbnails = $(".thumbnail-wrapper");
                        thumbnails.click(function() {
                            leaveClass(period);
                            joinClass(period, $(
                                this).data(
                                "teacher"));
                        });
                    }
                });
            },
            getCourseSuggestions = function(period) {
                $.ajax({
                    method: "GET",
                    cache: false,
                    url: conf.paths.getCourses,
                    data: {
                        query: conf.courseSearchField.val()
                    },
                    success: function(result) {
                        conf.courseSearchResults.html(
                            result);
                        $(".course-suggestion").click(
                            function() {
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
            };
        getSchedule();
    };
}(window.jQuery));