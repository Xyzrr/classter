(function($) {
	$.fn.validify = function(options, func) {
		return this.each(function() {
			var that = $(this);
			var opts;
			if(typeof(options) === "object") {
				opts = $.extend({}, $.fn.validify.defaults, options);
				that.data(opts);

				var hasError = false;
				for(var index in opts.rules) {
					if(!opts.rules[index].method()) {
						that.displayError(opts.rules[index].error);
						hasError = true;
					}
				}
				if(!hasError) {
					that.hideError();
					return true;
				} else {
					return false;
				}
			} else if(typeof(options) === "string") {
				that.displayError(options);
				if(typeof(func) !== "undefined") {
					func(that);
				}
				return false;
			}
		});
	};

	$.fn.validify.defaults = {
		position: "top",
		rules: [],
		success: function(){}
	};

	$.fn.displayError = function(html) {
		return this.each(function() {
			var div = $(this);
			if(html === undefined) {
				html = "An error occured.";
			}
			div.hideError();
			var alert = div.prev();

			var divOffset = div.offset();
			alert = $("<div class='validify' role='alert'></div>");
			var close = $("<button type='button' class='close'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>");
			close.click(function() {
				div.hideError(function(){});
			});
			alert.html(html).prepend(close);
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

	$.fn.hideError = function() {
		return this.each(function() {
			var alert = $(this).next();
			if(alert.length && alert.hasClass("validify")) {
				alert.fadeOut(200, function() {
					$(this).remove();
				});
			}
		});
	};

	$.fn.mustFollow = function(rules) {
		this.each(function() {
			var input = $(this);
			input.change(function() { //Show error if no error already exists and rule isn't followed
				var alert = input.next();
				if (alert.length && alert.hasClass("validify")) {
					return;
				}
				$.each(rules, function(index) {
					if (!rules[index].rule(input.val())) {
						input.parent().addClass("has-error");
						input.displayError(rules[index].html);
						return false;
					}
				});
			});
			input.on("keyup change", function() { //Hide error if error exists and rule is followed
				if (input.parent().hasClass("has-error")) {
					$.each(rules, function(index) {
						if (rules[index].rule(input.val())) {
							input.hideError();
							input.parent().removeClass("has-error");
						}
					});
				}
			});
		});
	};

	$.clearErrors = function() {
		$(".validify").remove();
	};
	
}(window.jQuery));