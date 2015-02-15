(function($) {
    'use strict';
    $.fn.editable = function(func) {
        if(func === "toggle") {
            return this.each(function() {
                var read = $(this).find("[data-profile='read']");
                var edit = $(this).find("[data-profile='edit']");
                read.toggle(200);
                edit.toggle(200);
            });
        } else {
            return this.each(function() {
                var that = $(this);
                var read = $(this).find("[data-profile='read']");
                var edit = $(this).find("[data-profile='edit']");
                var toggleEdit = function(e) {
                    e.preventDefault();
                    read.toggle(200);
                    edit.toggle(200);
                };
                edit.hide();
                that.find(
                    "[data-profile='toggle-edit'], [data-profile='cancel-edit']"
                ).click(toggleEdit);
                that.find("[data-profile='save-edit']").click(function(e) {
                    e.preventDefault();
                    func(that, read, edit);
                });
            });
        }
    };
}(window.jQuery));