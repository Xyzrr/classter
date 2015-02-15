<?php

$grade = (int) $_REQUEST["grade"];
$userID = $_SESSION["userID"];

echo $grade;

$db = new SqlConnection();

$db->query(
	"UPDATE Students SET
	grade = '${grade}'
	WHERE userID = ${userID}
	");