<?php

$about = $_REQUEST["about"];
$userID = $_SESSION["userID"];

echo Format::paragraphs(stripslashes($about));

$db = new SqlConnection();

$about = $db->escapeString($about);
$db->query("
	UPDATE Users SET
	about = '${about}'
	WHERE userID = ${userID}
	");