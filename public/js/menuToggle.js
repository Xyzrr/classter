//The menu toggle button toggles the sidebar on and off.
var menuToggle = (function() {
    var conf = {
            menuButton: $("#menu-toggle"),
            wrapper: $("#wrapper"),
            sidebar: $("#sidebar-wrapper"),
            toggledClass: "toggled"
        },
        init = function() {
            if(config.isMobile) {
                conf.menuButton.click(showMenu);
                $("body").click(function(e) {
                    e.preventDefault();
                    if($(e.target).closest(conf.menuButton).length === 0 && $(e.target).closest(conf.sidebar).length === 0) {
                        conf.wrapper.removeClass(conf.toggledClass);
                    }
                });
            } else {
                conf.menuButton.mouseenter(showMenu);
                conf.sidebar.mouseleave(hideMenu);
            }
        },
        showMenu = function(e) {
            e.preventDefault();
            conf.wrapper.addClass(conf.toggledClass);
        },
        hideMenu = function(e) {
            e.preventDefault();
            conf.wrapper.removeClass(conf.toggledClass);
        };
    return {
        init: init
    };
}());