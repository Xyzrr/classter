<?php

extract($_REQUEST);
extract($_SESSION);

$result = array();
$result["success"] = true;

$commentLength = strlen(strip_tags($commentBody));
if($commentLength < 20) {
	$result["success"] = false;
	$result["error"] = "The comment must be at least 20 characters. You entered " . $commentLength . ".";
}

if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

$db = new SqlConnection();
$userID = (int) $userID;

$commentBody = $db->escapeString($commentBody);

$db->query(
   "INSERT INTO Comments SET
	userID = ${userID},
	commentBody = '${commentBody}',
	creationDate = NOW(),
	postID = '${postID}'
");

echo json_encode($result);