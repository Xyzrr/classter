(function($) {
	$.fn.validify = function(options) {
		return this.each(function() {
			$(this).data($.extend({}, $.fn.validify.defaults, options));
		});
	};

	$.fn.validify.defaults = {
		position: "top"
	};

	$.fn.displayError = function(html, complete) {
		return this.each(function() {
			var div = $(this);
			if(html === undefined) {
				html = "An error occured.";
			}
			if(complete === undefined) {
				div.hideError(function(){});
			} else {
				div.hideError(complete);
			}
			var alert = div.prev();

			var divOffset = div.offset();
			alert = $(
				["<div class='alert alert-danger alert-dismissible' role='alert'>",
					"<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>",
					html,
				"</div>"
				].join("")
			);
			alert.insertAfter(div);
			var pointerClass;
			switch(div.data("position")) {
				case "top": pointerClass = "pointer-down"; break;
				case "bottom": pointerClass = "pointer-up"; break;
				case "left": pointerClass = "pointer-right"; break;
				case "right": pointerClass = "pointer-left"; break;
			}
			var pointer = $("<span class='pointer " + pointerClass + "'></span>");
			alert.append(pointer);
			var alertOffset = alert.offset();

			switch(div.data("position")) {
				case "top":
					pointer.offset({
						top: alertOffset.top + alert.outerHeight(),
						left: alertOffset.left + alert.outerWidth()/2 - pointer.outerWidth()/2
					});
					alert.offset({
						top: divOffset.top - alert.outerHeight() - pointer.outerHeight(),
						left: divOffset.left + div.outerWidth()/2 - alert.outerWidth()/2
					});
					break;
				case "bottom":
					pointer.offset({
						top: alertOffset.top - pointer.outerHeight(),
						left: alertOffset.left + alert.outerWidth()/2 - pointer.outerWidth()/2
					});
					alert.offset({
						top: divOffset.top + div.outerHeight() + pointer.outerHeight(),
						left: divOffset.left + div.outerWidth()/2 - alert.outerWidth()/2
					});
					break;
				case "left":
					pointer.offset({
						top: alertOffset.top,
						left: alertOffset.left + alert.outerWidth()
					});
					alert.offset({
						top: divOffset.top + div.outerHeight()/2,
						left: divOffset.left - alert.outerWidth() - pointer.outerWidth()
					});
					break;
				case "right":
					pointer.offset({
						top: alertOffset.top,
						left: alertOffset.left - pointer.outerWidth()
					});
					alert.offset({
						top: divOffset.top + div.outerHeight()/2,
						left: divOffset.left + div.outerWidth() + pointer.outerWidth()
					});
					break;
			}

			alert.hide().fadeIn(200);
		});
	};

	$.fn.hideError = function(complete) {
		return this.each(function() {
			var alert = $(this).next();
			if(alert.length && alert.hasClass("alert")) {
				alert.fadeOut(200, function() {
					$(this).remove();
					complete();
				});
			} else {
				complete();
			}
		});
	};

	$.fn.mustFollow = function(rules) {
		this.each(function() {
			var input = $(this);
			input.change(function() { //Show error if no error already exists and rule isn't followed
				var alert = input.next();
				if (alert.length && alert.hasClass("alert")) {
					return;
				}
				$.each(rules, function(index) {
					if (!rules[index].rule(input.val())) {
						input.parent().addClass("has-error").addClass("has-feedback");
						input.displayError(rules[index].html);
						return false;
					}
				});
			});
			input.on("keyup change", function() { //Hide error if error exists and rule is followed
				if (input.parent().hasClass("has-error")) {
					$.each(rules, function(index) {
						if (rules[index].rule(input.val())) {
							input.hideError(function() {
								input.parent().removeClass("has-error").removeClass(
									"has-feedback");
							});
						}
					});
				}
			});
		});
	};

	$.fn.checkErrors = function(errors, func) {
		return this.each(function() {
			var div = $(this);
			$.each(errors, function(index) {
				if(errors[index]) {
					if(func === undefined) {
						div.displayError(errors[index]);
					} else {
						div.displayError(errors[index], func(div));
					}
					return false;
				}
			});
		});
	};

	$.fn.checkError = function(error, func) {
		return this.each(function() {
			var div = $(this);
			if(error) {
				if(func === undefined) {
					div.displayError(error);
				} else {
					div.displayError(error, func(div));
				}
				return false;
			}
		});
	};

	$.clearErrors = function() {
		$(".alert").remove();
	};
	
}(window.jQuery));