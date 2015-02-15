var questions = (function(){
    var conf = {
        paths: {
            getQuestions: config.ajaxPath + "/getQuestions.php"
        },
        tabs: $(".nav>li>a")
    },
    init = function() {
        if($(".questions-body").length) {
            var options = {
                offset: 0,
                perPage: 10
            };
            getQuestions(options);
            conf.tabs.on("shown.bs.tab", function() {
                getQuestions(options);
            });

            console.log(options);
        }
    },
    getQuestions = function(options) {
        $.ajax({
            method: "GET",
            url: conf.paths.getQuestions,
            data: {
                tab: $(".nav>li.active>a").attr("href"),
                offset: options.offset,
                perPage: options.perPage
            },
            success: function(json) {
                var result = $.parseJSON(json);

                $(".tab-pane.active").html(result.questions);
                $("tr").tooltip();

                $(".pagination").paginator({
                    offset: 0,
                    perPage: 10,
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