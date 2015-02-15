<?php

$period = $_REQUEST["period"];
$teacherUserID = $_REQUEST["teacherUserID"];
$userID = $_SESSION["userID"];
$db = new SqlConnection();

$db->query(
	"SELECT classID
	FROM Classes
	INNER JOIN UserClasses USING (classID)
	WHERE period = ${period}
	AND userID = ${teacherUserID}
	");

if($db->numRows()) {
	$row = $db->fetchArray();
	$classID = $row["classID"];

	$db->query(
		"UPDATE Classes SET
		studentCount = studentCount + 1
		WHERE classID = ${classID}
		");

	$db->query(
		"INSERT INTO UserClasses
		SET classID = ${classID},
		userID = ${userID}");

} else {

	$db->query(
		"INSERT INTO Classes SET
		period = ${period},
		studentCount = 1
		");
	$classID = $db->lastID();

	$db->query(
		"INSERT INTO UserClasses SET
		userID = ${userID},
		classID = ${classID}
		");

	$db->query(
		"INSERT INTO UserClasses SET
		userID = ${teacherUserID},
		classID = ${classID}
		");
}

?>