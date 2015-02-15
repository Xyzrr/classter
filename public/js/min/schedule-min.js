!function($){"use strict";$.fn.schedule=function(){var e=$(this),a={paths:{getSchedule:config.ajaxPath+"/getSchedule.php",getClassSuggestions:config.ajaxPath+"/getClassSuggestions.php",getCourses:config.ajaxPath+"/getCourses.php",setCourseLevel:config.ajaxPath+"/setCourseLevel.php"},joinClassModal:$("#join-class"),teacherSearchField:$("#teacher-search"),setCourseModal:$("#set-course"),courseSearchField:$("#course-search"),teacherSearchResults:$("#teacher-search-results"),courseSearchResults:$("#course-search-results")},t=function(){$.ajax({method:"GET",url:a.paths.getSchedule,data:{targetID:e.data("id")},success:function(a){e.html(a);var t=$(".footable");t.footable(),s(),t.on("footable_row_expanded",s),t.on("footable_breakpoint",s),$("select").selectpicker(),$("select.course-level-selector").change(n)}})},s=function(){$(".set-period, .change-teacher").click(c),$(".change-course, .set-course").click(o)},c=function(){var e=$(this).val();a.joinClassModal.modal("show"),r(e),a.teacherSearchField.attr("placeholder","Your period "+e+" teacher's name"),setTimeout(function(){a.teacherSearchField.focus(),a.teacherSearchField.val("")},500),a.teacherSearchField.keyup(function(){r(e)})},o=function(){var e=$(this).val();a.setCourseModal.modal("show"),u(e),a.courseSearchField.attr("placeholder","Your period "+e+" course name"),setTimeout(function(){a.courseSearchField.focus(),a.courseSearchField.val("")},500),a.courseSearchField.keyup(function(){u(e)})},r=function(e){$.ajax({method:"GET",cache:!1,url:a.paths.getClassSuggestions,data:{query:a.teacherSearchField.val(),period:e},success:function(t){a.teacherSearchResults.html(t);var s=$(".thumbnail-wrapper");s.click(function(){l(e),h(e,$(this).data("teacher"))})}})},u=function(e){$.ajax({method:"GET",cache:!1,url:a.paths.getCourses,data:{query:a.courseSearchField.val()},success:function(t){a.courseSearchResults.html(t),$(".course-suggestion").click(function(){i(e,$(this).data("course"))})}})},h=function(e,s){a.joinClassModal.modal("hide"),$.ajax({method:"POST",url:config.ajaxPath+"/joinClass.php",data:{period:e,teacherUserID:s},success:function(){t()}})},l=function(e){$.ajax({method:"POST",url:config.ajaxPath+"/leaveClass.php",data:{period:e}})},i=function(e,s){a.setCourseModal.modal("hide"),$.ajax({method:"POST",url:config.ajaxPath+"/setCourse.php",data:{period:e,courseID:s},success:function(){t()}})},n=function(){var e=$(this).data("period");$.ajax({method:"GET",url:a.paths.setCourseLevel,data:{period:e,courseLevelID:$(this).val()}})};t()}}(window.jQuery);
//# sourceMappingURL=./schedule-min.map