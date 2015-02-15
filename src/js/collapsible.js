(function($) {
	$.fn.collapsible = function () {
		return $(this).each(function() {
			var div = $(this);

			var head = div.find("h2");

			var toggle = function () {
				div.toggleClass("collapsed");
			};

			head.click(toggle);
		});
	};
}(window.jQuery));

$(function() {
	$(".collapsible").collapsible();
});