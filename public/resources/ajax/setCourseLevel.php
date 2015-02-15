<?php

$db = new SqlConnection();

$period = $_REQUEST["period"];
$courseLevelID = (int) $_REQUEST["courseLevelID"];
$userID = $_SESSION["userID"];

$db->query(
	"SELECT *
	FROM Classes
	INNER JOIN (
		UserClasses
	) USING (classID)
	WHERE period = ${period}
	AND userID = ${userID}
	");
$row = $db->fetchArray();
$classID = $row["classID"];

if($courseLevelID == -1) {
	$courseLevelID = "NULL";
}

$db->query(
	"UPDATE Classes SET
	courseLevelID = ${courseLevelID}
	WHERE classID = ${classID}
	");