<?php	

$period = $_REQUEST["period"];
$userID = $_SESSION["userID"];

$db = new SqlConnection();

$db->query(
	"SELECT Classes.classID AS classID, studentCount FROM Classes
	INNER JOIN UserClasses USING (classID)
	WHERE period = ${period}
	AND userID = ${userID}");

if($db->numRows()) {
	extract($db->fetchArray());

	if($studentCount == 1) {
		//Delete Class
		$db->query(
			"DELETE FROM Classes
			WHERE classID = ${classID}");
	} else {
		//Remove user from class
		$db->query(
			"UPDATE Classes 
			SET studentCount = studentCount - 1
			WHERE classID = ${classID}
		");
		$db->query(
			"DELETE FROM UserClasses
			WHERE userID = ${userID}
			AND classID = ${classID}
		");
	}
}