//The menu toggle button toggles the sidebar on and off.
var menuToggle = (function() {
    var conf = {
            menuButton: $("#menu-toggle"),
            wrapper: $("#wrapper"),
            sidebar: $("#sidebar-wrapper"),
            toggledClass: "toggled",
            search: $(".search-all"),
            searchInput: $(".search-all>input"),
            searchButton: $(".search-all button"),
            dropdown: $(".user-dropdown"),
            navbarToggle: $(".navbar-toggle")
        },
        init = function() {
            if(config.isMobile) {
                conf.menuButton.click(showMenu);
                $("body").click(function(e) {
                    if($(e.target).closest(conf.menuButton).length === 0 && $(e.target).closest(conf.sidebar).length === 0) {
                        conf.wrapper.removeClass(conf.toggledClass);
                    }
                });
            } else {
                conf.menuButton.mouseenter(showMenu);
                conf.sidebar.mouseleave(hideMenu);
            }
            resizeSearch();
            $(window).resize(resizeSearch);
            conf.searchInput.focus(function() {
                setTimeout(resizeSearch, 10);
            });
            conf.searchInput.blur(resizeSearch);
            conf.searchInput.keypress(function(e) {
                if(e.which === 13) {
                    search();
                }
            });
            conf.searchButton.click(search);
        },
        showMenu = function(e) {
            e.preventDefault();
            conf.wrapper.addClass(conf.toggledClass);
        },
        hideMenu = function(e) {
            e.preventDefault();
            conf.wrapper.removeClass(conf.toggledClass);
        },
        resizeSearch = function() {
            if(conf.searchInput.is(":focus") && $(window).width() < 768) {
                conf.search.css({
                    left: 10,
                    width: $(window).width() - 20
                });
            } else {
                var width;
                if($(window).width() < 768) {
                    width = 73;
                } else {
                    width = conf.dropdown.width();
                }
                conf.search.css({
                    "max-width": 700,
                    left: 50 + conf.menuButton.width(),
                    width: $(window).width() - width - conf.menuButton.width() - 50
                });
            }
        },
        search = function() {
            if(document.URL.indexOf("people") === -1) {
                window.location = config.home + "/people/?q=" + conf.searchInput.val();
            }
        }
        ;
    return {
        init: init
    };
}());

$(function() {
    menuToggle.init();
});