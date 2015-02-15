<?php
class DaySchedule {
	private $userID;
	private $date;
	private $currentDate;
	private $dayScheduleID;
	private $dayScheduleReason;
	private $isSpecialDay;
	private $db;
	public $blocks;
	public $scheduleTime;
	public $scheduleInfo;

	function __construct($date, $userID) {
		$this->date = $date;
		$this->db = new SqlConnection();
		$this->userID = $userID;
		$this->currentDate = date("Y-m-d", time());
		$this->db->query(
			"SELECT dayScheduleID, reason, blockDayDependent FROM SpecialDays
			LEFT JOIN DaySchedules USING (dayScheduleID)
			WHERE date = '{$this->date}'");
		if($this->db->numRows() > 0) {
			extract($this->db->fetchArray());
			$this->dayScheduleID = $dayScheduleID;
			$this->dayScheduleReason = $reason;
			$this->isSpecialDay = true;
		} else {
			$this->dayScheduleID = 1;
			$this->isSpecialDay = false;
		}

		$this->getDaySchedule();
	}

	private function getDaySchedule() {
		$blockDay = Acadefly::getBlockDay($this->date);
		$blockDayID = $blockDay["blockDayID"];
		$blockDayName = $blockDay["blockDayName"];
		if($this->date == $this->currentDate) {
			$this->scheduleTime = "Today's Schedule";
		} elseif(strtotime($this->date) == strtotime($this->currentDate . " + 1 day")) {
			$this->scheduleTime = "Tomorrow's Schedule";
		} else {
			$readableDate = date("F j, Y", strtotime($this->date));
			$this->scheduleTime = "Schedule for " . $readableDate;
		}
		$this->db->query("SELECT dayScheduleName, blockDayDependent FROM DaySchedules WHERE dayScheduleID = {$this->dayScheduleID}");
		$row = $this->db->fetchArray();
		$this->scheduleInfo = "<div class='schedule-info-wrapper'>";
		if($row["blockDayDependent"]) {
			$this->db->query(
			"SELECT *
			FROM DayScheduleBlocks
			LEFT JOIN (
				BlockPeriods INNER JOIN (
					Classes 
					INNER JOIN UserClasses ON Classes.classID = UserClasses.classID AND UserClasses.userID = {$this->userID}
					LEFT JOIN Courses ON Classes.courseID = Courses.courseID
				) ON BlockPeriods.period = Classes.period
			) ON DayScheduleBlocks.dayScheduleBlockID = BlockPeriods.dayScheduleBlockID AND BlockPeriods.blockDayID = ${blockDayID}
			WHERE DayScheduleBlocks.dayScheduleID = {$this->dayScheduleID}
			ORDER BY startTime
			");
			$this->scheduleInfo .= "<span class='schedule-info block-day'><span class='number'>" . $blockDayName . "</span> Day</span>";
		} else {
			$this->db->query(
			"SELECT *
			FROM DayScheduleBlocks
			LEFT JOIN (
				Classes 
				INNER JOIN UserClasses ON Classes.classID = UserClasses.classID AND UserClasses.userID = {$this->userID}
				LEFT JOIN Courses ON Classes.courseID = Courses.courseID
			) ON DayScheduleBlocks.period = Classes.period
			WHERE DayScheduleBlocks.dayScheduleID = {$this->dayScheduleID}
			ORDER BY startTime
			");
		}
		if($this->isSpecialDay) {
			if(empty($this->dayScheduleReason)) {
				$this->scheduleInfo .= "<span class='schedule-info schedule-name'>" . $row["dayScheduleName"] . "</span>";
			} else {
				$this->scheduleInfo .= "<span class='schedule-info schedule-name'>" . $row["dayScheduleName"] . "<span class='reason'> - " . $this->dayScheduleReason . "</span></span>";
			}
		}
		$this->scheduleInfo .= "<span class='schedule-info day-of-week'>" . date("l", strtotime($this->date)) . "</span>";
		$this->scheduleInfo .= "</div>";

		while($row = $this->db->fetchArray()) {
			$blockName = $row["blockName"];
			$startTime = strtotime($row["startTime"]);
			$endTime = strtotime($row["endTime"]);
			$room = $row["room"];
			$classID = $row["classID"];

			//Set Course Name
			if($row["blockName"] == "Lunch") {
				$courseName = "Lunch";
			} elseif(empty($row["classID"])) {
				$courseName = "Unknown Class";
			} elseif(empty($row["courseName"])) {
				$courseName = "Unnamed Class";
			} else {
				$courseName = $row["courseName"];
			}

			$this->blocks[] = array(
				"blockName" => $blockName,
				"courseName" => $courseName,
				"startTime" => $startTime,
				"endTime" => $endTime,
				"room" => $room,
				"classID" => $classID,
				"class" => "");
		}
	}
}