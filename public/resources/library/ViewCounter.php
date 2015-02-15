<?php

class ViewCounter {
	private $db;

	function __construct() {
		$this->db = new SqlConnection();
	}

	function addPostView($postID) {
		extract($_SESSION);

		$this->db->query(
			"SELECT * FROM Views
			WHERE postID = ${postID}
			AND userID = ${userID}
		");
		if($this->db->numRows() == 0) {
			$this->db->query(
				"INSERT INTO Views SET
				userID = ${userID},
				postID = ${postID},
				expiryDate = DATE_ADD(NOW(), INTERVAL 1 HOUR)
			");
			$this->db->query(
				"UPDATE Posts SET
				viewCount = viewCount + 1
				WHERE postID = ${postID}
			");
		}
	}

	function addProfileView($profileID) {
		extract($_SESSION);

		$this->db->query(
			"SELECT * FROM Views
			WHERE profileID = ${profileID}
			AND userID = ${userID}
		");
		if($this->db->numRows() == 0) {
			$this->db->query(
				"INSERT INTO Views SET
				userID = ${userID},
				profileID = ${profileID},
				expiryDate = DATE_ADD(NOW(), INTERVAL 1 HOUR)
			");
			$this->db->query(
				"UPDATE Users SET
				profileViewCount = profileViewCount + 1
				WHERE userID = ${profileID}
			");
		}
	}
}