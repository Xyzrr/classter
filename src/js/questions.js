var questions = (function(){
    var conf = {
        paths: {
            getQuestions: config.ajaxPath + "/getQuestions.php"
        },
        tabs: $(".nav>li>a"),
        perPage: 10,
        offset: 0
    },
    init = function() {
        conf.offset = parseInt(acadefly.URLParam("os"), 10);
        if(!conf.offset) {
            conf.offset = 0;
        }
        if($(".questions-body").length) {
            var options = {
                offset: conf.offset,
                perPage: conf.perPage
            };
            getQuestions(options);
            conf.tabs.on("shown.bs.tab", function() {
                getQuestions(options);
            });
        }
    },
    getQuestions = function(options) {
        $.ajax({
            method: "GET",
            url: conf.paths.getQuestions,
            cache: false,
            data: {
                tab: $(".nav>li.active>a").attr("href"),
                offset: options.offset,
                perPage: options.perPage
            },
            success: function(json) {
                var result = $.parseJSON(json);

                $(".tab-pane.active").html(result.questions);

                $(".pagination").paginator({
                    offset: conf.offset,
                    perPage: conf.perPage,
                    size: 5,
                    total: result.postCount,
                    onClick: getQuestions
                });
            }
        });
    }
    ;
    return {
        init: init
    };
}());

$(function() {
    questions.init();
});