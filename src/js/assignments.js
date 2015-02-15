var assignments = (function() {
	var conf = {
		postPanel: $(".post-panel"),
		postForm: $("#post-assignment-form"),
		submit: $("#submit-assignment-button")
	},
	init = function() {
		conf.postPanel.find("[data-toggle='expand']").click(function() {
			$("#title").attr("placeholder", "Short Description");
			conf.postPanel.removeClass("collapsed");
		});
		conf.postPanel.find("[data-toggle='collapse']").click(function() {
			$("#title").attr("placeholder", "Add New Assignment");
			conf.postPanel.find("input[type='text'], textarea").val("");
			conf.postPanel.addClass("collapsed");
		});
		conf.submit.click(addAssignment);
	},
	addAssignment = function(e) {
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: config.ajaxPath + "/assignments.php?method=post",
			data: conf.postForm.serialize(),
			success: function(json) {
				var result = $.parseJSON(json);
				if(result.success) {
					console.log("SUCCESS!");
				} else {
					console.log("FAIL!");
				}
			}
		});
	}
	;
	return {
		init: init
	};
}());

$(function($) {
	$.fn.assignments = function() {
		var that = this;
		var getAssignments = function() {
			$.ajax({
				method: "GET",
				url: config.ajaxPath + "/assignments.php?method=get",
				success: function(json) {
					var result = $.parseJSON(json);
					renderAssignments(result.assignments);
				}
			});
		};
		var renderAssignments = function(assignments) {
			for(var index in assignments) {
				var assignment = assignments[index];
				var output = $("" +
				"<div class='post-wrapper'>" +
				"<div class='post'>" +
					"<div class='head'>" +
						"<img src='" + config.home + "/img/thumbnails/default-small.jpg' alt=' class='thumb'>" +
						"<div class='info'>" +
							"<h4>" + assignment.courseName + "</h4>" +
							"<p>5 hours ago</p>" +
						"</div>" +
					"</div>" +
					"<h3>" + assignment.postTitle + "</h3>" +
					"<div class='body'>" +
					"<p>" + assignment.postBody + "</p>" +
					"</div>" +
					"<div class='controls'>" +
					"<button class='btn btn-default'><i class='fa fa-check'></i></button>" +
					"</div>" +
					"<div class='comment-box' data-id='" + assignment.postID + "'>" +
					"</div>" +
				"</div>" +
				"</div>");
				console.log(output);
				that.append(output);
			}
			$(".comment-box").commentBox();
		};
		getAssignments();
	};
}(window.jQuery));

$(function() {
	$("#assignments").assignments();
	assignments.init();
});