(function($) {
    'use strict';
    $.fn.profileEdit = function(func) {
        return this.each(function() {
            var read = $(this).find("div[data-profile='read']");
            var edit = $(this).find("form[data-profile='edit']");

            var toggleEdit = function(e) {
                e.preventDefault();
                read.toggle(200);
                edit.toggle(200);
            };

            edit.hide();

            $(this).find("button[data-profile='toggle-edit'], button[data-profile='cancel-edit']").click(toggleEdit);
            $(this).find("button[data-profile='save-edit']").click(function(e) {
                toggleEdit(e);
                func(read, edit);
            });
        });
    };
}(window.jQuery));

var profile = (function() {
    var conf = {
        paths: {
            editAbout: config.ajaxPath + "/profile/edit-about.php",
            editInfo: config.ajaxPath + "/profile/edit-info.php",
            editName: config.ajaxPath + "/profile/edit-name.php",
            getOverview: config.ajaxPath + "/profile/getOverview.php",
            getSchedule: config.ajaxPath + "/profile/getSchedule.php",
            getPosts: config.ajaxPath + "/profile/getPosts.php",
            getReputation: config.ajaxPath + "/profile/getReputation.php"
        },
        tabs: {
            overview: $("a[href='#overview']"),
            schedule: $("a[href='#schedule-tab']"),
            posts: $("a[href='#posts']"),
            reputation: $("a[href='#reputation']")
        },
        tabContents: {
            overview: $("#overview"),
            schedule: $("#schedule-tab"),
            posts: $("#posts"),
            reputation: $("#reputation")
        },
        targetIDField: $("#target-id")
    },
    init = function() {
        if(conf.targetIDField.length) {
            $("#name").profileEdit(saveName);
            conf.targetID = conf.targetIDField.val();

            switch($(".nav>li.active>a").attr("href")) {
                case "#overview": getOverview(); break;
                case "#schedule-tab": getSchedule(); break;
                case "#posts": getPosts(); break;
                case "#reputation": getReputation(); break;
            }
            conf.tabs.schedule.on("shown.bs.tab", getSchedule);
            conf.tabs.overview.on("shown.bs.tab", getOverview);
            conf.tabs.posts.on("shown.bs.tab", getPosts);
            conf.tabs.reputation.on("shown.bs.tab", getReputation);
        }
    },
    getOverview = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getOverview,
            data: {
                targetID: conf.targetID
            },
            success: function(result) {
                conf.tabContents.overview.html(result);
                $("#about").profileEdit(saveAbout);
                $("#info").profileEdit(saveInfo);

                var repPanel = $("#rep-panel"),
                questionRep = parseInt(repPanel.find("#question-rep").val(), 10),
                answerRep = parseInt(repPanel.find("#answer-rep").val(), 10),
                reviewRep = parseInt(repPanel.find("#review-rep").val(), 10);

                var data = [
                    {
                        value: questionRep,
                        color:"#F7464A",
                        highlight: "#FF5A5E",
                        label: "Questions"
                    },
                    {
                        value: answerRep,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Answers"
                    },
                    {
                        value: reviewRep,
                        color: "#FDB45C",
                        highlight: "#FFC870",
                        label: "Class Reviews"
                    }
                ];

                var options = {
                    animateRotate: false
                };

                var ctx = $("#rep-pie-chart").get(0).getContext("2d");
                var repPieChart = new window.Chart(ctx).Pie(data, options);
                repPieChart.update();
            }
        });
    },
    getSchedule = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getSchedule,
            data: {
                targetID: conf.targetID
            },
            success: function(result) {
                conf.tabContents.schedule.html(result);
                $("#schedule").schedule();
            }
        });
    },
    getPosts = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getPosts,
            data: {
                targetID: conf.targetID
            },
            success: function(result) {
                conf.tabContents.posts.html(result);
            }
        });
    },
    getReputation = function() {
        $.ajax({
            method: "GET",
            url: conf.paths.getReputation,
            data: {
                targetID: conf.targetID
            },
            success: function(result) {
                conf.tabContents.reputation.html(result);
            }
        });
    },
    saveAbout = function(read, edit) {
        $.ajax({
            method: "POST",
            url: conf.paths.editAbout,
            data: edit.serialize(),
            success: function(result) {
                read.find("#about-read").html(result);
            }
        });
    },
    saveInfo = function(read, edit) {
        $.ajax({
            method: "POST",
            url: conf.paths.editInfo,
            data: edit.serialize(),
            success: function(result) {
                read.find("#grade-read").html(result);
            }
        });
    },
    saveName = function(read, edit) {
        $.ajax({
            method: "POST",
            url: conf.paths.editName,
            data: edit.serialize(),
            success: function(result) {
                read.find("h1").html(result);
            }
        });
    }
    ;
    return {
        init: init
    };
})();

$(function() {
    profile.init();
});