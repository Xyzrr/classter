<?php

extract($_REQUEST);
extract($_SESSION);

$result = array();
$result["success"] = true;

if(strlen($title) < 15) {
	$result["success"] = false;
	$result["title"] = "The title must be at least 15 characters.";
}

$bodyLength = strlen(strip_tags($postBody));
if($bodyLength < 30) {
	$result["success"] = false;
	$result["body"] = "The body must be at least 30 characters. You entered " . $bodyLength . ".";
}

if (empty($courseID)) {
	$result["success"] = false;
	$result["subjectSelector"] = "Please select a subject.";
}

if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

$db = new SqlConnection();
$userID = (int) $userID;

$title = $db->escapeString($title);
$postBody = $db->escapeString($postBody);

$db->query(
   "INSERT INTO Posts SET
	userID = ${userID},
	postTitle = '${title}',
	postBody = '${postBody}',
	creationDate = NOW(),
	courseID = ${courseID},
	postTypeID = 1
");

$result["postID"] = $db->lastID();
echo json_encode($result);