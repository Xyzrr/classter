<?php

$layout = new BasicLayout("time-until-school.php");
$layout->render();
?>

<script>
var days = $("#days");
var hours = $("#hours");
var minutes = $("#minutes");
var seconds = $("#seconds");

var DateDiff = {
 	inSeconds: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();
 
        return parseInt((t2-t1)/1000);
 	},

 	inMinutes: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();
 
        return parseInt((t2-t1)/(1000*60));
 	},

 	inHours: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();
 
        return parseInt((t2-t1)/(1000*3600));
 	},

    inDays: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();
 
        return parseInt((t2-t1)/(24*3600*1000));
    }
}
 
var dString = "September 4, 2014 7:50:00";
var d1 = new Date(dString);

var refreshDate = function() {
	var d2 = new Date();

	days.text(DateDiff.inDays(d2, d1));
	hours.text(DateDiff.inHours(d2, d1)%24);
	minutes.text(DateDiff.inMinutes(d2, d1)%60);
	seconds.text(DateDiff.inSeconds(d2, d1)%60);
}

$(function() {
	refreshDate();
	window.setInterval(refreshDate, 1000);
})

</script>

