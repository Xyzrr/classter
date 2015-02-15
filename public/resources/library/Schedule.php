<?php

class Schedule {
	private $head = "
	<table class='footable table toggle-arrow'>
		<thead>
			<tr>
				<th>Period</th>
				<th>Course</th>
				<th>Level</th>
				<th data-hide='phone'>Teacher</th>
				<th data-hide='all'>Room</th>
			</tr>
		</thead>
	";
	private $tail = "</table>";
	private $userID;
	private $targetID;

	function __construct($targetID) {
		$this->targetID = $targetID;
	}

	public function render() {
		extract($_SESSION);
		if($this->targetID == $userID) {
			$classes = $this->getClasses($this->targetID);
			$this->printSchedule($classes);
		} else {
			$classes = $this->getClasses($this->targetID);
			$myClasses = $this->getClasses($userID);
			$this->printPublicSchedule($classes, $myClasses);
		}
	}

	private function printSchedule($classes) {
		echo $this->head;
		for($i = 1; $i <= 8; $i++) {
			echo "<tr>";
			if(empty($classes[$i])) {
				echo "
				<td class='set-period-wrapper' colspan='4'>
					<button class='set-period btn btn-default' value='${i}'>
						${i}
					</button>
				</td>
				";
			} else {
				Format::htmlspecialArray($classes[$i]);
				extract($classes[$i]);
				$teacherName = Acadefly::getName($classes[$i]);
				if(empty($courseName)) {
					$courseCell = "
					<button class='set-course btn btn-default' value='${i}'><i class='fa fa-plus'></i>Set Course Name</button>
					";
				} else {
					$courseCell = "
					<a class='table-cell-link' href='" . HOME . "/classes/?id=${classID}'></a>
					<button class='change-course btn btn-default' value='${i}'><i class='fa fa-pencil'></i></button>
					${courseName}
					";
				}
				$options = "";
				$ids = array(-1, 1, 2, 3);
				$names = array("N/A", "CP", "H", "AP");
				for($j = 0; $j < 4; $j++) {
					if($courseLevelID == $j) {
						$options .= "<option value=${ids[$j]} selected>${names[$j]}</option>";
					} else {
						$options .= "<option value=${ids[$j]}>${names[$j]}</option>";
					}
				}
				echo "
				<td class='one-line'>${period}</td>
				<td class='cell-with-button one-line'>
					${courseCell}
				</td>
				<td class='cell-with-button'>
					<select class='course-level-selector' data-period='${i}'>
						${options}
					</select>
				</td>
				<td class='cell-with-button'>
					<a class='table-cell-link' href='" . HOME . "/profile/?id=${teacherUserID}'></a>
					<button class='change-teacher btn btn-default' value='${i}'><i class='fa fa-pencil'></i></button>
					${teacherName}
				</td>
				<td class='cell-with-button'>
					<div class='change-room' data-period='${i}'>
						<button class='btn btn-default' data-profile='toggle-edit'><i class='fa fa-pencil'></i></button>
				        <span data-profile='read' id='room-read'>${room}</span>
						<form class='input-group' data-profile='edit'>
					      <input type='text' class='form-control' value='${room}' id='room'>
					      <span class='input-group-btn'>
					        <input class='btn btn-primary' type='submit' data-profile='save-edit' value='Save'/>
					      </span>
					    </form>
				    </div>
				</td>
				";
			}
			echo "</tr>";
		}
		echo $this->tail;
	}

	private function printPublicSchedule($classes, $myClasses) {
		if(count($classes) == 0) {
			echo "
				<div class='no-schedule'>
					<i class='fa fa-search fa-3x'></i>
					<p>This user has not set up their schedule.</p>
				</div>
			";
		} else {
			echo $this->head;
			foreach($classes as $period=>$class) {				
				Format::htmlspecialArray($class);
				extract($class);
				$teacherName = Acadefly::getName($class);
				if(empty($courseName)) {
					$courseName = "No named course";
				}
				if(!empty($myClasses[$period]) and $classID == $myClasses[$period]["classID"]) {
					echo "<tr class='common-class'>";
				} else {
					echo "<tr>";
				}
				echo "
				<td>${period}</td>
				<td>
					<a class='table-cell-link' href='" . HOME . "/classes/?id=${classID}'></a>
					${courseName}
				</td>
				<td>
					${courseLevelName}
				</td>
				<td>
					<a class='table-cell-link' href='" . HOME . "/profile/?id=${teacherUserID}'></a>
					${teacherName}
				</td>
				<td>
					${room}
				</td>
				";
				echo "</tr>";
			}
			echo $this->tail;
		}
	}

	private function getClasses($id) {
		$classes = array();
		$connection = new SqlConnection();
		$connection->query(
			"SELECT *, Teachers.userID AS teacherUserID
			FROM Classes
			INNER JOIN (
				UserClasses x
				LEFT JOIN (
					Users INNER JOIN Teachers ON Teachers.userID = Users.userID
				) ON x.userID = Users.userID
			) ON Classes.classID = x.classID
			INNER JOIN (
				UserClasses y
			) ON Classes.classID = y.classID AND y.userID = ${id}
			LEFT JOIN Courses ON Classes.courseID = Courses.courseID
			LEFT JOIN CourseLevels ON Classes.courseLevelID = CourseLevels.courseLevelID
			WHERE teacherID IS NOT NULL
			ORDER BY period
			");
		while($row = $connection->fetchArray()) {
			$classes[$row["period"]] = $row;
		}
		return $classes;
	}
}