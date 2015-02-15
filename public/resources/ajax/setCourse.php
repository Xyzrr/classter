<?php

$connection = new SqlConnection();

$period = $_REQUEST["period"];
$courseID = $_REQUEST["courseID"];
$userID = $_SESSION["userID"];

$connection->query("
	SELECT *
	FROM Classes
	INNER JOIN (
		UserClasses
	) USING (classID)
	WHERE period = ${period}
	AND userID = ${userID}
	");
$row = $connection->fetchArray();
$classID = $row["classID"];

$connection->query("
	UPDATE Classes SET
	courseID = ${courseID}
	WHERE classID = ${classID}
	");