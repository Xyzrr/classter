var tabs = (function() {
    var conf = {
        tabLinks: $("ul.nav-pills>li>a")
    },
    init = function() {
        storeCurrentTab();
        switchToSelectedTab();
        conf.tabLinks.on("show.bs.tab", function() {
            acadefly.URLParam("os", 0);
        });
    },
    storeCurrentTab = function() {
        conf.tabLinks.on("shown.bs.tab", function (e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        });
    },
    switchToSelectedTab = function() {
        var hash = window.location.hash;
        $('a[href="' + hash + '"]').tab('show');
    };
    return {
        init: init
    };
}());

(function($) {
    $.fn.classSelector = function() {
        var that = $(this);
        $.ajax({
            method: "GET",
            url: config.ajaxPath + "/getClasses.php",
            success: function(json) {
                var result = $.parseJSON(json);

                var wrapper = $("<div class='btn-group' data-toggle='buttons'></div>");

                that.append(wrapper);

                for(var index in result) {
                    var c = result[index];
                    console.log(c);
                    wrapper.append($("" +
                    "<label class='btn btn-primary active'>" +
                    "<input type='checkbox'>" + c.courseName + " " + c.courseLevelName +
                    "</label>"));
                }
            }
        });
    };
}(window.jQuery));

$(function() {
    var classSelectors = $("#class-selector");
    if(classSelectors.length) {
        classSelectors.classSelector();
    }
});