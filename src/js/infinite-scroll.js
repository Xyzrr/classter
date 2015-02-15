(function($) {
	$.fn.infiniteScroll = function(options) {
		var that = $(this);

		var opts;
		var keepTrying = true;

		var element_in_scroll = function(elem) {
			var docViewTop = $(window).scrollTop();
			var docViewBottom = docViewTop + $(window).height();

			var elemTop = elem.offset().top;
			var elemBottom = elemTop + elem.height();

			return (elemBottom <= docViewBottom + opts.offset);
		};

		var tryFetching = function() {
			var lastElement = that.find(opts.selector + ":last-child");
			if (that.html() === "" || element_in_scroll(lastElement)) {
				var count = that.find(opts.selector).length;
				opts.ajaxFunction(count);
				return true;
			}
			return false;
		};

		if(typeof(options) === "object") {
			opts = $.extend({}, $.fn.infiniteScroll.defaults, options);
			that.data("options", opts);
			$(document).scroll(tryFetching);
		} else {
			opts = that.data("options");
			if(options === "try" && keepTrying) {
				tryFetching();
			} else if(options === "stopTrying") {
				keepTrying = false;
			} else if(options === "startTrying") {
				keepTrying = true;
			}
		}
	};

	$.fn.infiniteScroll.defaults = {
		ajaxFunction: function(){},
		selector: ".item",
		offset: 0
	};
}(window.jQuery));