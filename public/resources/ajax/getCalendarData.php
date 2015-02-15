<?php

$db = new SqlConnection();

$specialDaysContent = array();
$specialDays = array();
$db->query("SELECT * FROM SpecialDays LEFT JOIN DaySchedules USING (dayScheduleID)");

while($row = $db->fetchArray()) {
	$specialDays[$row["date"]] = $row;
}

$db->query("SELECT startDate, endDate FROM Schools WHERE schoolID = 1");
extract($db->fetchArray());
$db->query("SELECT * FROM BlockDays WHERE schoolID = 1");
$blockDayCount = $db->numRows();
while($row = $db->fetchArray()) {
	$blockDays[(int) $row["cycleNumber"]] = $row["blockDayName"];
}
//Set Block Days
$cycleNumber = 1;
while (strtotime($startDate) <= strtotime($endDate)) {
	$dayOfWeek = (int) date("w", strtotime($startDate));
	if($dayOfWeek !== 0 and $dayOfWeek !== 6 and (!isset($specialDays[$startDate]) or $specialDays[$startDate]["skipBlock"] == "0")) {
		$blockDayName = $blockDays[$cycleNumber];
		$date = date("m-d-Y", strtotime($startDate));
		$specialDaysContent[$date] = "<div class='block-day'>${blockDayName}</div>";

		if($cycleNumber == $blockDayCount) {
			$cycleNumber = 1;
		} else {
			$cycleNumber ++;
		}
	}
	$startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
}
//Set special days
foreach($specialDays as $specialDay) {
	$date = date("m-d-Y", strtotime($specialDay["date"]));
	$dayScheduleID = $specialDay["dayScheduleID"];
	if(empty($specialDaysContent[$date])) {
		$specialDaysContent[$date] = "<div class='special-day special-day-${dayScheduleID}'></div>";
	} else {
		$specialDaysContent[$date] .= "<div class='special-day special-day-${dayScheduleID}'></div>";
	}
}
echo json_encode($specialDaysContent);