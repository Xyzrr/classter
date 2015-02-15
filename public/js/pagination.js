(function($) {
	$.fn.paginator = function(options) {

		var opts = $.extend({}, $.fn.paginator.defaults, options);
		var offsetFromURL = acadefly.URLParam("os");
		if(offsetFromURL) {
			opts.offset = parseInt(offsetFromURL, 10);
		}

		return this.each(function() {
			var paginator = $(this);
			var currentPage = Math.floor(opts.offset/opts.perPage) + 1;
			var maxPage = Math.ceil(opts.total/opts.perPage);
			var i;
			var str = "";

			if(maxPage <= opts.size) { //Render basic paginator
				if(maxPage !== 1) {
					for(i = 1; i <= maxPage; i++) {
						if(currentPage === i) {
							str += "<li class='active'><a>" + i + "</a></li>";
						} else {
							opts.offset = opts.perPage*(i-1);

							str += "<li>";
							str += "<a class='paginator-button' data-os='" + opts.offset + "'>" + i + "</a>";
							str += "</li>";
						}
					}
				}

			} else { //Render advanced paginator

				var middle = Math.floor(opts.size/2);
				var startPage = 1;
				if(currentPage <= middle) {
					startPage = 1;
				} else if(currentPage > maxPage - opts.size + middle) {
					startPage = maxPage - opts.size + 1;
				} else {
					startPage = currentPage - middle;
				}

				var offset;
				offset = opts.offset - opts.perPage;
				if(currentPage === 1) {
					str += "<li class='disabled'><a>&laquo;</a></li>";
				} else {
					str += "<li>";
					str += "<a class='paginator-button' data-os='" + offset + "'>&laquo;</a>";
					str += "</li>";
				}

				for(i = startPage; i < startPage + opts.size; i++) {
					if(currentPage === i) {
						str += "<li class='active'><a>" + i + "</a></li>";
					} else {
						offset = opts.perPage*(i-1);

						str += "<li>";
						str += "<a class='paginator-button' data-os='" + offset + "'>" + i + "</a>";
						str += "</li>";
					}
				}

				offset = opts.offset + opts.perPage;
				if(currentPage === maxPage) {
					str += "<li class='disabled'><a>&raquo;</a></li>";
				} else {
					str += "<li>";
					str += "<a class='paginator-button' data-os='" + offset + "'>&raquo;</a>";
					str += "</li>";
				}
			}

			paginator.html(str);
			paginator.find(".paginator-button").click(function() {
				var offset = $(this).data("os");
				acadefly.URLParam("os", offset);
				paginator.paginator($.extend({}, opts, {offset: offset}));

				opts.onClick({
					offset: offset,
					perPage: opts.perPage
				});
			});
			opts.complete();
		});
	};

	$.fn.paginator.defaults = {
		offset: 0,
		perPage: 5,
		total: 10,
		size: 5,
		onClick: function(){},
		complete: function(){}
	};
}(window.jQuery));