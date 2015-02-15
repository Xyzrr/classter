$(function() {

	var $calendar = $( '#calendar' ),
	cal = $calendar.calendario( {
		onDayClick : function( $el, $contentEl, dateProperties ) {
			showEvents(dateProperties );
		},
		displayWeekAbbr : true,
		weekabbrs : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT' ],
		months : [ 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER' ],
		startIn: 0
	} ),
	$month = $( '#custom-month' ).html( cal.getMonthName() ),
	$year = $( '#custom-year' ).html( cal.getYear() );

	$( '#custom-next' ).on( 'click', function() {
		cal.gotoNextMonth( updateMonthYear );
	} );
	$( '#custom-prev' ).on( 'click', function() {
		cal.gotoPreviousMonth( updateMonthYear );
	} );

	$.ajax({
		method: "GET",
		url: config.ajaxPath + "/getCalendarData.php",
		success: function(json) {
			var result = $.parseJSON(json);
			cal.setData(result);
		}
	});

	function updateMonthYear() {
		$month.html( cal.getMonthName() );
		$year.html( cal.getYear() );
	}

	// just an example..
	function showEvents(dateProperties) {

		hideEvents();
		
		var $events = $( '<div id="custom-content-reveal" class="custom-content-reveal"><h4>' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4></div>' ),
			$close = $( '<span class="custom-content-close"><i class="fa fa-times"></i></span>' ).on( 'click', hideEvents );
		var $eventsBody = $("<div class='body'></div>");
		$events.append($close).append($eventsBody).insertAfter( $calendar );
		
		setTimeout( function() {
			$events.css( 'top', '0%' );
		}, 25 );

		$.ajax({
			method: "GET",
			url: config.ajaxPath + "/getDayInfo.php",
			data: dateProperties,
			success: function(result) {
				$eventsBody.append($(result));
			}
		});

	}
	function hideEvents() {

		var $events = $( '#custom-content-reveal' );
		if( $events.length > 0 ) {
			$events.css( 'top', '100%');
		}
		setTimeout(function() {
			$events.remove();
		}, 600);
	}

});