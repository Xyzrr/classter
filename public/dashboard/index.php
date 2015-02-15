<?php
	Acadefly::verifyUserIsLoggedIn();

	class Dashboard {
		private $userID;
		private $db;
		private $currentDate;
		private $dayOfWeek;
		private $error = "";
		private $status = "";
		private $blockDayName = "";
		private $output = "";

		function __construct() {
			$this->userID = $_SESSION["userID"];
			$this->db = new SqlConnection();

			$this->currentDate = date("Y-m-d");
			// $this->currentDate = date("Y-m-d", strtotime("September 4, 2014"));
			$this->dayOfWeek = (int) date("w", strtotime($this->currentDate));

			$this->createDaySchedule();
			$this->render();
		}

		private function createDaySchedule() {
			$this->checkForErrors();

			$isWeekend = Acadefly::isWeekend($this->currentDate);
			if($isWeekend) {
				$this->setMessage("No School Today", "Weekend");
			}

			$isHoliday = Acadefly::isHoliday($this->currentDate, $this->db);
			if($isHoliday) {
				$this->setMessage("No School Today", $isHoliday);
			}

			$isInSchoolYear = Acadefly::isInSchoolYear($this->currentDate, $this->db);
			if($isInSchoolYear == 1) {
				$this->setMessage("No School Today", "School hasn't started yet");
			}
			if($isInSchoolYear == 2) {
				$this->setMessage("No School Today", "School's out!");
			}

			//Get current schedule
			if(!$isInSchoolYear and !$isWeekend and !$isHoliday) {
				$daySchedule = new DaySchedule($this->currentDate, $this->userID);
				$blocks = $this->renderProgressBar($daySchedule->blocks);
				$content = $this->renderDayScheduleContent($blocks);
				$this->addScheduleToOutput("", $daySchedule->scheduleTime, $daySchedule->scheduleInfo, $content);
			}
			//Get tomorrow's schedule
			$tomorrow = date("Y-m-d", strtotime($this->currentDate . " + 1 day"));
			if(!Acadefly::isInSchoolYear($tomorrow, $this->db) and !Acadefly::isWeekend($tomorrow) and !Acadefly::isHoliday($tomorrow, $this->db)) {
				$daySchedule = new DaySchedule($tomorrow, $this->userID);
				$this->blocks = $daySchedule->blocks;
				$content = $this->renderDayScheduleContent($daySchedule->blocks);
				$this->addScheduleToOutput(" collapsed", $daySchedule->scheduleTime, $daySchedule->scheduleInfo, $content);
			}
		}

		private function checkForErrors() {
			$this->db->query(
				"SELECT COUNT(classID) AS userClassCount FROM UserClasses
				WHERE userID = {$this->userID}");
			extract($this->db->fetchArray());
			if($userClassCount == 0) {
				$this->error = "
				<div class='alert alert-warning' role='alert'>Your schedule is not set up! To make full use of the dashboard, please <a href='" . HOME . "/profile/#schedule-tab'>create your schedule.</a></div>
				";
			}
		}

		private function setProgressBar($variables) {
			if(isset($variables["title"])) {
				$this->status .= "<h4>{$variables['title']}</h4>";
			}
			if(isset($variables["start-time"])) {
				$this->status .= "<input type='hidden' id='start-time' value='{$variables['start-time']}'/>";
			}
			if(isset($variables["end-time"])) {
				$this->status .= "<input type='hidden' id='end-time' value='{$variables['end-time']}'/>";
			}
		}

		private function setTimer($variables) {
			if(isset($variables["target-time"])) {
				$this->status .= "<input type='hidden' id='target-time' value='{$variables['target-time']}'/>";
			}
			if(isset($variables["label"])) {
				$this->status .= "<input type='hidden' id='label' value='{$variables['label']}'/>";
			}
			if(isset($variables["target-direction"])) {
				$this->status .= "<input type='hidden' id='target-direction' value='{$variables['target-direction']}'/>";
			}
		}

		private function setMessage($header, $body) {
			$this->status = "
			<h3>${header}</h3>
			<p>${body}</p>
			";
		}

		private function renderProgressBar($blocks) {
			$currentTime = time();
			foreach($blocks as $key=>$block) {
				extract($block);
				if ($startTime <= $currentTime and $currentTime <= $endTime) {
					$blocks[$key]["class"] = " class='active'";
					$this->setProgressBar(array(
						"title" => "Progress in " . $courseName,
						"start-time" => $startTime,
						"end-time" => $endTime));
				} elseif($key == 0 and $currentTime <= $startTime) {
					$this->setTimer(array(
						"label" => "Until School Begins",
						"target-time" => $startTime,
						"target-direction" => "until"
					));
					$blocks[0]["class"] = " class='incoming'";
				} elseif($key > 0 and $blocks[$key-1]["endTime"] <= $currentTime and $currentTime <= $startTime) {
					$this->setProgressBar(array(
						"title" => "Switching to " . $courseName,
						"start-time" => $blocks[$key-1]["endTime"],
						"end-time" => $startTime));
					$blocks[$key]["class"] = " class='incoming'";
				} elseif($key == count($blocks) - 1 and $currentTime > $endTime) {
					$this->setTimer(array(
						"label" => "Since School Dismissed",
						"target-time" => $endTime,
						"target-direction" => "since"
					));
					$blocks[count($blocks) - 1]["class"] = " class='outgoing'";
				}
			}
			return $blocks;
		}

		private function addScheduleToOutput($scheduleClass, $scheduleTime, $scheduleInfo, $content) {
			$this->output .= "
			<div class='collapsible day-schedule-wrapper${scheduleClass}'>
				<h2>${scheduleTime}</h4>
				<div class='body'>
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
			</div>";
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

		private function render() {
			$layout = new MainLayout("dashboard.php", array(
				"blockDayName" => $this->blockDayName, 
				"status" => $this->status, 
				"output" => $this->output,
				"error" => $this->error,
				"scripts" => array("collapsible", "dashboard")));
			$layout->render();
		}
	}

	$dashboard = new Dashboard();
?>