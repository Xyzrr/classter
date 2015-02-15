<?php

$firstName = $_REQUEST["firstName"];
$lastName = $_REQUEST["lastName"];
$userID = $_SESSION["userID"];

$db = new SqlConnection();

$firstName = $db->escapeString(stripslashes($firstName));
$lastName = $db->escapeString(stripslashes($lastName));

$db->query("
	UPDATE Users SET
	firstName = '${firstName}',
	lastName = '${lastName}'
	WHERE userID = ${userID}
	");

$db->query("
	SELECT * FROM Users
	LEFT JOIN Students USING (userID)
	LEFT JOIN Teachers USING (userID)
	WHERE userID = ${userID}
	");
$user = $db->fetchArray();
echo Acadefly::getName($user);