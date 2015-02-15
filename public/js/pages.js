//The "pages" page displays all homework pages of classes in iframes.
var pages = (function() {
    var conf = {
            frames: $(".page-frame, .mobile-iframe"),
            urlField: $("#url-input"),
        },
        init = function() {
            resizeiframes();
            $(window).resize(function() {
                resizeiframes();
            });
            checkForMobile();
            hideSettingsOnWelcome();
            $("#url-submit").click(goToURL);
            // var loader = "<i class='fa fa-spinner fa-spin'></i>";
            // var finished = "<i class='fa fa-check'></i>";
            $("#save-url-button").click(saveURL);
            $("#home-button").click(goHome);
            $("#homework-button").click(goToHomework);
            $(".settings").click(toggleSettings);
        },
        hideSettingsOnWelcome = function() {
            if (!($("li.active").length)) {
                $(".settings-wrapper").hide();
            }
        },
        toggleSettings = function() {
            $(".settings-wrapper").slideToggle();
        },
        goToHomework = function() {
            var homeworkURL = $("li.active>.page-tab").data("url");
            $(".tab-pane.active>iframe").attr("src", homeworkURL);
        },
        goHome = function() {
            var homeURL = $("li.active>.page-tab").data("home");
            $(".tab-pane.active>iframe").attr("src", homeURL);
        },
        saveURL = function() {
            if (conf.urlField.val() === "") {
                conf.urlField.attr("placeholder", "Please enter a URL.");
            } else {
                var newurl = addhttp(conf.urlField.val());
                $("li.active>.page-tab").data("url", newurl);
                $(".tab-pane.active>iframe").attr("src", newurl);
                setHomeworkURL(newurl, $("li.active>.page-tab").data(
                    "class"));
            }
        },
        goToURL = function(e) {
            e.preventDefault();
            var url = conf.urlField.val();
            url = addhttp(url);
            $(".tab-pane.active>iframe").attr("src", url);
        },
        checkForMobile = function() {
            if (config.isMobile) {
                $(".page").addClass("mobile-iframe");
            }
        },
        resizeiframes = function() {
            conf.frames.height(window.innerHeight - 95);
        },
        addhttp = function(url) {
            if (!url.match(/^[a-zA-Z]+:\/\//)) {
                url = 'http://' + url;
            }
            return url;
        },
        setHomeworkURL = function(url, classID) {
            $.ajax({
                method: "POST",
                url: config.ajaxPath + "/setHomeworkURL.php",
                data: {
                    url: url,
                    classID: classID
                },
                success: function() {
                    //Success indicator
                }
            });
        };
    return {
        init: init
    };
}());

$(function() {
    pages.init();
});