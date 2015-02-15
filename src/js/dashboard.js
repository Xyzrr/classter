(function($) {
	$.fn.blockProgress = function() {
		var that = $(this);
		var outerProgress = $('<div class="progress" id="block-progress"></div>');
		var innerProgress = $('<div class="progress-bar progress-bar-striped active"></div>'),
		timeLeftIndicator = $('<div class="time-left-wrapper"></div>'),
		timeLeftBubble = $('<div class="time-left"></div>'),
		timeLeftCaret = $('<div class="time-left-caret"></div>'),
		timer = $("<span class='timer'></span>"),
		data = {
			start: $("#start-time").val(),
			end: $("#end-time").val(),
			target: $("#target-time").val(),
			label: $("#label").val(),
			direction: $("#target-direction").val()
		};
		var updateProgress = function() {
			var currentTime = new Date();
			var progress = (currentTime.getTime()/1000 - data.start)/(data.end - data.start)*100;
			if(progress > 100) {
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			innerProgress.css("width", progress + "%");
			var timeLeft = data.end - currentTime.getTime()/1000;
			var minutesLeft = Math.floor((timeLeft%3600)/60);
			var secondsLeft = Math.floor(timeLeft%60);
			timeLeftBubble.html("<span class='count'>" + minutesLeft + "</span>" + "m <span class='count'>" + secondsLeft + "</span>s left");
			timeLeftIndicator.css("left", innerProgress.position().left + innerProgress.width() - timeLeftIndicator.width()/2);
		};
		var updateTimer = function() {
			var currentTime = new Date();
			var timeDifference;
			if(data.direction === "until") {
				timeDifference = data.target - currentTime.getTime()/1000;
			} else {
				timeDifference =  currentTime.getTime()/1000 - data.target;
			}
			if(timeDifference < 0) {
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			var hours = Math.floor(timeDifference/3600);
			var minutes = Math.floor((timeDifference%3600)/60);
			var seconds = Math.floor(timeDifference%60);
			timer.html("<div class='timer-unit'><span class='timer-count'>" + hours + "</span><span class='timer-label'>" + "HOURS" + "</span></div>" +
						"<div class='timer-unit'><span class='timer-count'>" + minutes + "</span><span class='timer-label'>" + "MINUTES" + "</span></div>" +
						"<div class='timer-unit'><span class='timer-count'>" + seconds + "</span><span class='timer-label'>" + "SECONDS" + "</span></div>");
		};
		var pad = function(num, size) {
			return ('000000000' + num).substr(-size);
		};
		if(data.start) {
			var startDate = new Date(data.start*1000);
			var endDate = new Date(data.end*1000);
			that.append($("<span class='begin-time'>" + (((startDate.getHours()+11)%12)+1) + ":" + pad(startDate.getMinutes(), 2) + "</span>"));
			that.append($("<span class='end-time'>" + (((endDate.getHours()+11)%12)+1) + ":" + pad(endDate.getMinutes(), 2) + "</span>"));
			that.append(outerProgress).append(timeLeftIndicator);
			timeLeftIndicator.append(timeLeftCaret).append(timeLeftBubble);
			outerProgress.append(innerProgress);
			updateProgress();
			setInterval(updateProgress, 1000);
		} else if(data.target) {
			that.append(timer);
			that.append($("<span class='timer-title'>" + data.label + "</span>"));
			updateTimer();
			setInterval(updateTimer, 1000);
		}
	};
}(window.jQuery));

$(function() {
	$(".block-progress-wrapper>.body").blockProgress();
});