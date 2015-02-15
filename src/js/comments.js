(function ($) {
	'use strict';
	$.fn.commentBox = function () {
		return this.each(function() {
			var commentBox = $(this),
			conf = {
				paths: {
					getCommentBox: config.ajaxPath + "/getCommentBox.php",
					postComment: config.ajaxPath + "/postComment.php",
					deleteComment: config.ajaxPath + "/deleteComment.php"
				},
			},
			init = function() {
				$.ajax({
					method: "GET",
					url: conf.paths.getCommentBox,
					data: {
						postID: commentBox.data("id")
					},
					success: function(result) {
						commentBox.html(result);
						commentBox.find(".comment-textarea").validify({
							position: "top"
						});
						commentBox.find(".comment-button").click(function() {
							commentBox.find(".comment-form").show(200);
							commentBox.find(".comment-textarea").focus();
							commentBox.find(".comment-button-wrapper").hide(200);
						});
						commentBox.find(".cancel").click(function() {
							commentBox.find(".comment-button-wrapper").show(200);
							commentBox.find(".comment-form").hide(200);
						});
						commentBox.find(".post-comment").click(postComment).validify({
							position: "right"
						});
						commentBox.find(".delete-button").click(function() {
							deleteComment($(this).parent().parent());
						}).validify({
							position: "right"
						});
					}
				});
			},
			postComment = function() {
				$.ajax({
					method: "POST",
					url: conf.paths.postComment,
					data: {
						postID: commentBox.data("id"),
						commentBody: commentBox.find(".comment-textarea").val()
					},
					success: function(json) {
						var result = $.parseJSON(json);
						if(result.success) {
							init();
						} else {
							commentBox.find(".comment-textarea").validify(result.error);
						}
					},
					error: function() {
						commentBox.find(".post-comment").displayError();
					}
				});
			},
			deleteComment = function(commentWrapper) {
				$.ajax({
					method: "POST",
					url: conf.paths.deleteComment,
					data: {
						commentID: commentWrapper.data("id")
					},
					success: function(json) {
						var result = $.parseJSON(json);
						if(result.success) {
							commentWrapper.hide(200);
						} else {
							commentWrapper.find(".delete-button").validify(result.error);
						}
					},
					error: function() {
						commentWrapper.find(".delete-button").displayError();
					}
				});
			};
			init();
		});
	};
}(window.jQuery));