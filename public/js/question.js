var question = (function() {
    var conf = {
        paths: {
            getAnswers: config.ajaxPath + "/getAnswers.php",
            postAnswer: config.ajaxPath + "/postAnswer.php",
            getQuestion: config.ajaxPath + "/getQuestion.php",
            getAnswerBox: config.ajaxPath + "/getAnswerBox.php",
            deletePost: config.ajaxPath + "/deletePost.php"
        },
        answers: $("#answers"),
        writeAnswer: $("#write-answer-panel"),
        question: $("#question"),
        answerBox: $("#answer-box"),
        showAnswerBoxButton: $("#show-answer-box"),
        deleteQuestionModal: $("#delete-question-confirmation"),
        deleteQuestionConfirm: $("#delete-question-confirm")
    },
    init = function() {
        if(conf.question.length) {
            getQuestion();
            getAnswers();
        }
        if(conf.showAnswerBoxButton.length) {
            conf.showAnswerBoxButton.click(getAnswerBox);
        } else {
            getAnswerBox();
        }
        conf.deleteQuestionConfirm.click(deleteQuestion);
        conf.deleteQuestionConfirm.validify({
            position: "left"
        });
    },
    getQuestion = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getQuestion,
            data: {
                postID: conf.question.data("id")
            },
            success: function(result) {
                conf.question.html(result);
                conf.question.find(".rep-box").voteBox();
                conf.question.find(".comment-box").commentBox();
                conf.question.find(".delete-post").click(function() {
                   conf.deleteQuestionModal.modal("show");
                });
            }
        });
    },
    getAnswers = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getAnswers,
            data: {
                postID: conf.question.data("id")
            },
            success: function(result) {
                conf.answers.html(result);
                conf.answers.find(".rep-box").voteBox();
                conf.answers.find(".comment-box").commentBox();
                conf.answers.find(".delete-post").validify({
                    position: "left"
                }).click(function() {
                    deleteAnswer($(this).parents(".answer-panel"));
                });
            }
        });
    },
    postAnswer = function(e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: conf.paths.postAnswer,
            data: {
                postID: conf.question.data("id"),
                postBody: conf.answerEditor.cleanHtml()
            },
            success: function(json) {
                var result = $.parseJSON(json);
                if(result.success) {
                    getAnswers();
                    conf.answerBox.remove();
                } else {
                    conf.answerEditor.checkErrors([result.error, result.serverError], function(div) {
                        div.parent().addClass("has-error");
                    });
                }
            },
            error: function() {
                conf.postAnswerButton.displayError();
            }
        });
    },
    deleteAnswer = function(answerPanel) {
        $.ajax({
            method: "POST",
            url: conf.paths.deletePost,
            data: {
                postID: answerPanel.data("id")
            },
            success: function(json) {
                var result = $.parseJSON(json);
                if(result.success) {
                    answerPanel.hide(200);
                } else {
                    answerPanel.find(".delete-answer").checkErrors([result.error]);
                }
            },
            error: function() {
                answerPanel.find(".delete-answer").displayError();
            }
        });
    },
    deleteQuestion = function() {
        $.ajax({
            method: "POST",
            url: conf.paths.deletePost,
            data: {
                postID: conf.question.data("id")
            },
            success: function(json) {
                var result = $.parseJSON(json);
                if(result.success) {
                    document.location.href = "?";
                } else {
                   conf.deleteQuestionConfirm.checkErrors([result.error]);
                }
            },
            error: function() {
                conf.deleteQuestionConfirm.displayError();
            }
        });
    },
    getAnswerBox = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getAnswerBox,
            success: function(result) {
                conf.answerBox.html(result).hide().fadeIn(300);
                conf.postAnswerButton = $("#post-answer");
                conf.postAnswerButton.click(postAnswer).validify({
                    position: "right"
                });
                $.initWysiwyg();

                conf.answerEditor = $("#editor");
                conf.answerEditor.validify({
                    position: "top"
                });
            }
        });
    }
    ;
    return {
        init: init
    };
}());

$(function() {
    question.init();
});