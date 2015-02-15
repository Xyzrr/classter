(function ($) {
	'use strict';
	$.fn.voteBox = function () {
		return this.each(function() {
			var repBox = $(this),
			conf = {
				paths: {
					vote: config.ajaxPath + "/vote.php",
					getVoteBox: config.ajaxPath + "/getVoteBox.php"
				},
				upArrow: repBox.find(".up-arrow"),
				rep: repBox.find(".rep"),
				downArrow: repBox.find(".down-arrow")
			},
			init = function() {
				$.ajax({
					method: "GET",
					url: conf.paths.getVoteBox,
					data: {
						postID: repBox.data("id")
					},
					success: function(result) {
						repBox.html(result);

						conf.upArrow = $(repBox.find(".up-arrow"));
						conf.rep = repBox.find(".rep");
						conf.downArrow = repBox.find(".down-arrow");

						conf.upArrow.click(function() {
							vote("up");
						});
						conf.downArrow.click(function() {
							vote("down");
						});

						repBox.validify({
							position: "right"
						});
					}
				});
			},
			vote = function(direction) {
				$.ajax({
					method: "POST",
					url: conf.paths.vote,
					data: {
						postID: repBox.data("id"),
						direction: direction
					},
					success: function(json) {
						var result = $.parseJSON(json);
						if(!result.success) {
							repBox.checkErrors([result.error]);
						} else {
							conf.upArrow.removeClass("active");
							conf.downArrow.removeClass("active");
							if(result.newVote) {
								if(result.newVote === "up") {
									conf.upArrow.addClass("active");
								} else {
									conf.downArrow.addClass("active");
								}
							}
							conf.rep.text(parseInt(conf.rep.text(), 10) + result.scoreChange);
						}
					},
					error: function() {
						repBox.displayError();
					}
				});
			};
			init();
		});
	};
}(window.jQuery));