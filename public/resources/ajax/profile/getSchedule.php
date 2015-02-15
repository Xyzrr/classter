<?php
	extract($_REQUEST);
	extract($_SESSION);

	class PublicProfile {
		private $currentDate;
		private $output;
		private $userID;
		private $db;

		function __construct($userID) {
			$this->db = new SqlConnection();
			$this->currentDate = date("Y-m-d");
			$this->userID = $userID;
			$this->createDaySchedule();
			echo $this->output;
		}

		private function findCurrentSpot($blocks) {
			$currentTime = time();
			foreach($blocks as $key=>$block) {
				extract($block);
				if ($startTime <= $currentTime and $currentTime <= $endTime) {
					$blocks[$key]["class"] = " class='active'";
				} elseif($key == 0 and $currentTime <= $startTime) {
					$blocks[0]["class"] = " class='incoming'";
				} elseif($key > 0 and $blocks[$key-1]["endTime"] <= $currentTime and $currentTime <= $startTime) {
					$blocks[$key]["class"] = " class='incoming'";
				} elseif($key == count($blocks) - 1 and $currentTime > $endTime) {
					$blocks[count($blocks) - 1]["class"] = " class='outgoing'";
				}
			}
			return $blocks;
		}

		private function createDaySchedule() {
			if(!Acadefly::isInSchoolYear($this->currentDate, $this->db) and !Acadefly::isWeekend($this->currentDate) and !Acadefly::isHoliday($this->currentDate, $this->db)) {
				$daySchedule = new DaySchedule($this->currentDate, $this->userID);
				$blocks = $daySchedule->blocks;
				$blocks = $this->findCurrentSpot($blocks);
				$content = $this->renderDayScheduleContent($blocks);
				$this->addScheduleToOutput($daySchedule->scheduleTime, $daySchedule->scheduleInfo, $content);
			}
		}

		private function addScheduleToOutput($scheduleTime, $scheduleInfo, $content) {
			$this->output .= "
			<div class='col-full'>
			    <div class='profile-panel'>
			        <div class='profile-panel-header'>
			            <h2>Current Schedule</h2>
					</div>    
					<table class='table day-schedule'>
						<thead>
							<tr>
								<th colspan='3'>${scheduleInfo}</th>
							</tr>
						</thead>
						<tbody>
							${content}
						</tbody>
					</table>
				</div>
			</div>
				";
		}

		private function renderDayScheduleContent($blocks) {
			$content = "";
			if(!empty($blocks)) {
				foreach($blocks as $block) {
					extract($block);
					$readableStartTime = date("g:i", $startTime);
					$readableEndTime = date("g:i", $endTime);
					$link = "";
					if(!empty($classID)) {
						$link = "<a class='table-cell-link' href='" . HOME . "/classes/?id=${classID}'></a>";
					}
					$content .= "
					<tr${class}>
						<td>${readableStartTime} - ${readableEndTime}</td>
						<td>${link}${courseName}</td>
						<td>${room}</td>
					</tr>
					";
				}
			}
			return $content;
		}
	}

	if((int) $targetID !== (int) $userID) {	
		$profile = new PublicProfile($targetID);
	}
?>
<div class="col-full">
    <div class="profile-panel">
        <div class="profile-panel-header">
            <h2>Full Schedule</h2>
        </div>
        <div id="schedule" data-id="<?= $targetID ?>">
        </div>
    </div>
</div>