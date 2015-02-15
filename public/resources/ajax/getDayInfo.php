<?php

class Day {
	private $db;
	private $showSchedule = true;
	private $date;
	private $output = "";
	function __construct($date) {
		$this->date = $date;
		$this->db = new SqlConnection();

		$isInSchoolYear = Acadefly::isInSchoolYear($this->date, $this->db);
		if($isInSchoolYear) {
			if($isInSchoolYear == 1) {
				$this->output .= "
					<div class='no-school'>No School</div>
					<div class='schedule-reason'>School hasn't begin yet at this time</div>
				";
			} else {
				$this->output .= "
					<div class='no-school'>No School</div>
					<div class='schedule-reason'>School is over at this time</div>
				";
			}
		} elseif(Acadefly::isWeekend($this->date)) {
			$this->output .= "
				<div class='no-school'>No School</div>
				<div class='schedule-reason'>Weekend</div>"
			;
		} else {
			$isHoliday = Acadefly::isHoliday($this->date, $this->db);
			if($isHoliday) {
				$this->output .= "
				<div class='no-school'>No School</div>
				<div class='schedule-reason'>" . $isHoliday . "</div>
				";
			} else {
				$daySchedule = new DaySchedule($this->date, $_SESSION["userID"]);
				$this->scheduleTime = $daySchedule->scheduleTime;
				$this->scheduleInfo = $daySchedule->scheduleInfo;
				$this->blocks = $daySchedule->blocks;
			}
		}
	}

	private function renderDaySchedule() {
		if(!empty($this->blocks)) {
			$this->output .="
				<table class='table day-schedule'>
					<thead>
						<tr>
							<th colspan='3'>Schedule{$this->scheduleInfo}</th>
						</tr>
					</thead>
					<tbody>
			";

			foreach($this->blocks as $block) {
				extract($block);
				$readableStartTime = date("g:i", $startTime);
				$readableEndTime = date("g:i", $endTime);
				$this->output .= "
				<tr${class}>
					<td>${blockName}</td>
					<td>${courseName}</td>
					<td>${readableStartTime} - ${readableEndTime}</td>
				</tr>
				";
			}

			$this->output .= "
					</tbody>
				</table>
			";
		}
	}

	public function render() {
		$this->renderDaySchedule();
		echo $this->output;
	}
}


extract($_GET);
$date = sprintf("%04d-%02d-%02d", $year, $month, $day);
$day = new Day($date);
$day->render();