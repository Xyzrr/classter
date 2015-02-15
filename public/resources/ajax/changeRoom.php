<?php

$db = new SqlConnection();

extract($_REQUEST);
extract($_SESSION);

$period = (int) $period;
$room = $db->escapeString($room);

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

$db->query(
	"UPDATE Classes SET
	room = '${room}'
	WHERE classID = ${classID}
	");