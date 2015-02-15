<?php

$url = $_REQUEST["url"];
$classID = $_REQUEST["classID"];
$userID = $_SESSION["userID"];

$connection = new SqlConnection();

$connection->query("
	UPDATE Classes SET
	homeworkURL = '${url}'
	WHERE classID = ${classID}
	");