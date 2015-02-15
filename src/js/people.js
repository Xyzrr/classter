//The people tab displays a list of all users based on a query.
var people = (function() {
    var conf = {
        paths: {
            getPeople: config.ajaxPath + "/getPeople.php"
        },
        listPeopleContainer: $("#people-list"),
        searchField: $(".search-all>input"),
        searchButton: $(".search-all>button"),
        canFetch: true,
        tab: "all"
    },
    init = function() {
        if(!config.isMobile) {
            conf.searchField.focus();
        }
        var query = acadefly.URLParam("q");
        if(query) {
            conf.searchField.val(query);
        }
        var tab = acadefly.URLParam("t");
        if(tab) {
            $(".radio-nav label").removeClass("active");
            $("#" + tab).addClass("active");
            conf.tab = tab;
        }
        $(".radio-nav").click(function() {
            setTimeout(function() {
                var val = $(".radio-nav label.active input").val();
                acadefly.URLParam("t", val);
                conf.tab = val;
                getPeople();
            }, 10);
        });
        var timeout;
        conf.searchField.keyup(function() {
            conf.listPeopleContainer.fadeOut(100);
            clearTimeout(timeout);
            timeout = setTimeout(getPeople, 100);
        });
        conf.searchButton.click(getPeople);
        conf.listPeopleContainer.infiniteScroll({
            ajaxFunction: getMorePeople,
            selector: ".thumbnail-wrapper",
            offset: 240
        });
        getPeople();
    },
    getPeople = function() {
        acadefly.URLParam("q", conf.searchField.val());
        $.ajax({
            method: "GET",
            url: conf.paths.getPeople,
            data: {
                query: conf.searchField.val(),
                filter: conf.tab,
                offset: 0,
                count: Math.floor(conf.listPeopleContainer.width()/160)*(Math.floor($(window).height()/211)+2)
            },
            success: function(json) {
                var result = $.parseJSON(json);
                if(result.length > 0) {
                    conf.listPeopleContainer.html("");
                    for(var index in result) {
                        var wrapper = createPersonWrapper(result[index]);
                        conf.listPeopleContainer.append(wrapper);
                    }
                } else {
                    conf.listPeopleContainer.html("<div class='no-results'>" +
                        "<h1><i class='fa fa-search'></i></h1>" +
                        "<p>No results for " + conf.searchField.val() + "</p>" +
                    "</div>");
                }
                conf.listPeopleContainer.fadeIn(100);
            }
        });
    },
    getMorePeople = function(offset) {
        if(conf.canFetch) {
            conf.canFetch = false;
            $.ajax({
                method: "GET",
                url: conf.paths.getPeople,
                data: {
                    query: conf.searchField.val(),
                    filter: conf.tab,
                    offset: offset,
                    count: (conf.listPeopleContainer.width()/160)*2 //2 rows
                },
                success: function(json) {
                    var result = $.parseJSON(json);
                    for(var index in result) {
                        var wrapper = createPersonWrapper(result[index]);
                        conf.listPeopleContainer.append(wrapper);
                    }
                    conf.canFetch = true;
                }
            });
        }
    },
    createPersonWrapper = function(person) {
        var thumbnail = $("<img src='" + person.thumbnail + "'/>");
        var image = $("<div class='image'></div>");
        image.append(thumbnail);
        var name = $("<span class='name'>" + person.name + "</span>");
        var role = $("<span class='role'>" + person.role + "</span>");
        var info = $("<div class='info'></div>");
        info.append([name, role]);
        var link = $("<a href='" + config.home + "/profile/?id=" + person.userID + "'></a>");
        var wrapper = $("<div class='thumbnail-wrapper'>");
        wrapper.append([image, info, link, wrapper]);
        return wrapper;
    }
    ;
    return {
        init: init
    };
}());

$(function() {
    people.init();
});