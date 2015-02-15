<?php

class Classroom {
	private $classID;
	private $db;
	public $info;
	public $teachers = array();
	public $students = array();

	function __construct ($classID) {
		$this->classID = $classID;
		$this->db = new SqlConnection();
		if($this->fetchInfo()) {
			$this->fetchTeachers();
			$this->fetchStudents();
		}
	}

	private function fetchInfo () {
		$this->db->query(
			"SELECT * FROM Classes
			LEFT JOIN Courses USING (courseID)
			LEFT JOIN CourseLevels USING (courseLevelID)
			WHERE classID = {$this->classID}
			");
		if($this->db->numRows()) {		
			$this->info = $this->db->fetchArray();
			return true;
		} else {
			return false;
		}
	}

	private function fetchTeachers () {
		$this->db->query(
			"SELECT * FROM Users
			LEFT JOIN Thumbnails USING (thumbnailID)
			INNER JOIN UserClasses ON Users.userID = UserClasses.userID AND UserClasses.classID = {$this->classID}
			INNER JOIN Teachers ON Teachers.userID = Users.userID
			");
		while($row = $this->db->fetchArray()) {
			$this->teachers[] = $row;
		}
	}

	private function fetchStudents () {
		$this->db->query(
			"SELECT * FROM Users
			LEFT JOIN Thumbnails USING (thumbnailID)
			INNER JOIN UserClasses ON Users.userID = UserClasses.userID AND UserClasses.classID = {$this->classID}
			INNER JOIN Students ON Students.userID = Users.userID
			");
		while($row = $this->db->fetchArray()) {
			$this->students[] = $row;
		}
	}
}