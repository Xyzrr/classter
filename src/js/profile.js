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
            profilePicture: $(".profile-picture"),
            profilePictureCaption: $("#profile-picture-caption"),
            profilePictureFile: $("#profile-picture-file"),
            profilePictureForm: $("#profile-picture-form"),
            profilePictureImage: $("#profile-picture-image"),
            targetIDField: $("#target-id")
        },
        init = function() {
            if (conf.targetIDField.length) {
                $("#name").editable(saveName);
                conf.targetID = conf.targetIDField.val();
                switch ($(".nav>li.active>a").attr("href")) {
                    case "#overview":
                        getOverview();
                        break;
                    case "#schedule-tab":
                        getSchedule();
                        break;
                    case "#posts":
                        getPosts();
                        break;
                    case "#reputation":
                        getReputation();
                        break;
                }
                conf.tabs.schedule.on("shown.bs.tab", getSchedule);
                conf.tabs.overview.on("shown.bs.tab", getOverview);
                conf.tabs.posts.on("shown.bs.tab", getPosts);
                conf.tabs.reputation.on("shown.bs.tab", getReputation);
            }
            conf.profilePictureCaption.click(function() {
                conf.profilePictureFile.click();
            });
            conf.profilePictureFile.change(uploadProfilePicture);
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
                    $("#about").editable(saveAbout);
                    $("#info").editable(saveInfo);
                    var repPanel = $("#rep-panel"),
                        questionRep = parseInt(repPanel.find("#question-rep").val(), 10),
                        answerRep = parseInt(repPanel.find("#answer-rep").val(), 10),
                        reviewRep = parseInt(repPanel.find("#review-rep").val(), 10);
                    var data = [{
                        value: questionRep,
                        color: "#F7464A",
                        highlight: "#FF5A5E",
                        label: "Questions"
                    }, {
                        value: answerRep,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Answers"
                    }, {
                        value: reviewRep,
                        color: "#FDB45C",
                        highlight: "#FFC870",
                        label: "Class Reviews"
                    }];
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
        saveAbout = function(div, read, edit) {
            $.ajax({
                method: "POST",
                url: conf.paths.editAbout,
                data: edit.serialize(),
                success: function(result) {
                    div.editable("toggle");
                    read.find("#about-read").html(result);
                }
            });
        },
        saveInfo = function(div, read, edit) {
            $.ajax({
                method: "POST",
                url: conf.paths.editInfo,
                data: edit.serialize(),
                success: function(result) {
                    div.editable("toggle");
                    read.find("#grade-read").html(result);
                }
            });
        },
        saveName = function(div, read, edit) {
            $.ajax({
                cache: false,
                method: "POST",
                url: conf.paths.editName,
                data: edit.serialize(),
                success: function(json) {
                    var result = $.parseJSON(json);
                    if(result.success) {
                        read.find("h1").html(result.name);
                        div.editable("toggle");
                    } else {

                    }
                }
            });
        },
        uploadProfilePicture = function() {
            var file = this.files[0];
            var isValid = verifyProfilePicture(file);
            if (isValid) {
                var img = document.createElement("img");
                img.src = window.URL.createObjectURL(file);
                setTimeout(function() {
                    var smallCanvas = createThumbnail(img, 40);
                    var mediumCanvas = createThumbnail(img, 150);
                    var largeCanvas = createThumbnail(img, 200);
                    // var formData = new FormData();
                    // formData.append("smallThumbnail", smallCanvas.toDataURL("image/png"));
                    // formData.append("mediumThumbnail", mediumCanvas.toDataURL("image/png"));
                    // formData.append("largeThumbnail", largeCanvas.toDataURL("image/png"));
                    // console.log(largeCanvas.toDataURL("image/png"));
                    var smallImage = smallCanvas.toDataURL("image/jpeg");
                    var mediumImage = mediumCanvas.toDataURL("image/jpeg");
                    var largeImage = largeCanvas.toDataURL("image/jpeg");
                    console.log(largeImage);
                    conf.profilePictureImage.attr("src", largeImage);
                    $.ajax({
                        url: config.ajaxPath + "/uploadProfilePicture.php",
                        method: "POST",
                        data: {
                            smallThumbnail: smallImage.substring(1),
                            mediumThumbnail: mediumImage.substring(1),
                            largeThumbnail: largeImage.substring(1)
                            // smallThumbnail: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAICAgICAQICAgICAgIDAwYEAwMDAwcFBQQGCAcICAgHCAgJCg0LCQkMCggICw8LDA0ODg4OCQsQEQ8OEQ0ODg7/2wBDAQICAgMDAwYEBAYOCQgJDg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg7/wAARCAAgACADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwDtv2qvhLqvxB+LEOg6z8Y4LX7XZJB/ZU2nfbns0R/nR/3yQonz/P8AJ51eWa9+19qXwg8E2fhP4c/D29sXl1TUrqDQdHP2aw021eaH7M+zyX2b/wDSH8lNiPv875N9cL8ILq2svhVqukalrepR3lpqMt7/AMJPrGqfZLObWLt32PcS/O77/v7H+TfFvd9iVj/Df4b+Kviv8Z/BPhvSIZ9AfxH9ovH1W5ne7+zWUL7Jrt/7/wA6Oifc3u6V4dfO5xnCvhoc3MftOV+GkJe1w2d1fYey/wC3vhi5fl6v77n6lfs2/tUfDf8AaM8Dwpo1zH4f+Itla7tb8JXj/wCk239+aJ/+W0O/+NPnT+NEpP2lrCd4PhV4gm8N6j4p8NaF4uW68QWFhZfa3e1dP44v40r8yYh8Sv2av2x/FU2syyaUmjx/aIdb07wpcXdneWuzYl3LcJC7w23zp5z/APAPnr9ZfhpqfxC8d/Aj",
                            // mediumThumbnail: "medium",
                            // largeThumbnail: "large"
                        },
                        success: function() {
                            // conf.profilePictureImage.attr("src", result);
                            // console.log(result);
                        },
                        error: function() {
                            conf.profilePicture.displayError();
                        },
                        cache: false
                    });
                }, 200);
            }
        },
        createThumbnail = function(img, size) {
            var canvas = document.createElement("canvas");
            var ctx = canvas.getContext("2d");
            var MAX_SIZE = size;
            var width = img.width;
            var height = img.height;
            var xOffset = 0;
            var yOffset = 0;
            var ratio;
            if (width > height) {
                ratio = width/height;
                if (height > MAX_SIZE) {
                    width = ratio*MAX_SIZE;
                    height = MAX_SIZE;
                    xOffset = -(ratio-1)*MAX_SIZE/2;
                    canvas.width = MAX_SIZE;
                    canvas.height = MAX_SIZE;
                } else {
                    canvas.width = height;
                    canvas.height = height;
                    xOffset = -(ratio-1)*width/2;
                }
            } else {
                ratio = height/width;
                if (width > MAX_SIZE) {
                    height = ratio*MAX_SIZE;
                    width = MAX_SIZE;
                    yOffset = -(ratio-1)*MAX_SIZE/2;
                    canvas.width = MAX_SIZE;
                    canvas.height = MAX_SIZE;
                } else {
                    canvas.width = width;
                    canvas.height = width;
                    yOffset = -(ratio-1)*height/2;
                }
            }
            ctx.drawImage(img, xOffset, yOffset, width, height);
            return canvas;
        },
        verifyProfilePicture = function(file) {
            return conf.profilePicture.validify({
                position: "bottom",
                rules: [{
                    method: function() {
                        return file.type.match(/image.*/);
                    },
                    error: "This file is not an image."
                }, {
                    method: function() {
                        return file.size < 10485760;
                    },
                    error: "Must be under 10 MB."
                }]
            });
        };
    return {
        init: init
    };
})();
$(function() {
    profile.init();
});