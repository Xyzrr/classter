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