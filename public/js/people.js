//The people tab displays a list of all users based on a query.
var people = (function() {
    var conf = {
        paths: {
            getPeople: config.ajaxPath + "/getPeople.php"
        },
        listPeopleContainer: $("#people-list"),
        searchField: $("#search-people"),
        searchButton: $("#search-button")
    },
    init = function() {
        conf.searchField.keyup(getPeople);
        conf.searchButton.click(getPeople);
        getPeople();
    },
    getPeople = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getPeople,
            data: {
                query: conf.searchField.val(),
            },
            success: function(result) {
                conf.listPeopleContainer.html(result);
            }
        });
    }
    ;
    return {
        init: init
    };
}());

$(function() {
    people.init();
});