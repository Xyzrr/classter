var askQuestion = (function(){
    var conf = {
        paths: {
            getCourses: config.ajaxPath + "/getCourses.php",
            postQuestion: config.ajaxPath + "/postQuestion.php"
        },
        subjectSelector: $("#target-selector-course"),
        setCourseModal: $("#set-course"),
        courseSearchField: $("#course-search"),
        courseSearchResults: $("#course-search-results"),
        targetRadio: $(".target-radio"),
        postQuestionButton: $("#post-question"),
        questionEditor: $("#editor"),
        questionTitle: $("#title")
    },
    init = function() {
        $.initWysiwyg();
        $("select").selectpicker();
        conf.select = $(".bootstrap-select");
        conf.subjectSelector.click(changeCourse);
        conf.postQuestionButton.click(postQuestion);
        conf.questionTitle.validify({
            position: "top"
        });
        conf.select.validify({
            position: "top"
        });
        conf.subjectSelector.validify({
            position: "top"
        });
        conf.questionEditor.validify({
            position: "top"
        });
        conf.postQuestionButton.validify({
            position: "right"
        });
        conf.questionTitle.mustFollow([
            {
                rule: function(val) {
                    return (val.length >= 15);
                },
                html: "The title must have at least 15 characters"
            }
        ]);
        conf.subjectSelector.mustFollow([
            {
                rule: function(val) {
                    return (parseInt(val, 10));
                },
                html: "Please select a subject."
            }
        ]);
        conf.questionEditor.mustFollow([{rule: function(){return true;}}]);
        // conf.subjectSelector.change(function() {
        //     $(this).hideError();
        //     $(this).parent().removeClass("has-error");
        // });
    },
    changeCourse = function(e) {
        e.preventDefault();
        conf.setCourseModal.modal("show");
        getCourseSuggestions();
        conf.courseSearchField.attr("placeholder", "Subject of Question");
        setTimeout(function() {
            conf.courseSearchField.focus();
        }, 500);
        conf.courseSearchField.keyup(function() {
            getCourseSuggestions();
        });
    },
    getCourseSuggestions = function() {
        $.ajax({
            method: "GET",
            cache: false,
            url: conf.paths.getCourses,
            data: {
                query: conf.courseSearchField.val(),
            },
            success: function(result) {
                conf.courseSearchResults.html(result);
                $(".course-suggestion").click(function() {
                    var text = $(this).find(".course-name").text();
                    var courseID = $(this).data("course");
                    conf.setCourseModal.modal("hide");
                    conf.subjectSelector.removeClass("btn-primary").addClass("btn-success").html(text).val(courseID).trigger("change");
                });
            }
        });
    },
    postQuestion = function(e) {
        e.preventDefault();
        var target;
        $.ajax({
            method: "POST",
            url: conf.paths.postQuestion,
            data: {
                target: target,
                courseID: conf.subjectSelector.val(),
                title: $("#title").val(),
                postBody: conf.questionEditor.cleanHtml()
            },
            success: function(json) {
                var result = $.parseJSON(json);
                if(result.success) {
                    document.location.href = "../?id=" + result.postID;
                } else {
                    conf.questionTitle.validify(result.title, function(div) {
                        div.parent().addClass("has-error");
                    });
                    conf.questionEditor.validify(result.body, function(div) {
                        div.parent().addClass("has-error");
                    });
                    conf.subjectSelector.validify(result.subjectSelector, function(div) {
                        div.parent().addClass("has-error");
                    });
                }
            },
            error: function() {
                conf.postQuestionButton.displayError();
            }
        });
    }
    ;
    return {
        init: init
    };
}());

$(function() {
    askQuestion.init();
});