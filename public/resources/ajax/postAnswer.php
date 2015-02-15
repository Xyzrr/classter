<?php

extract($_POST);
extract($_SESSION);

$result["success"] = true;
$answerLength = strlen(strip_tags($postBody));
if($answerLength < 30) {
	$result["success"] = false;
	$result["error"] = "Your answer must be at least 30 characters. You entered " . $answerLength . ".";
}
if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

$db = new SqlConnection();
$postBody = $db->escapeString($postBody);

$db->query(
   "INSERT INTO Posts SET
	userID = ${userID},
	parentID = ${postID},
	postBody = '${postBody}',
	creationDate = NOW(),
	postTypeID = 2
");

$db->query(
	"UPDATE Posts SET
	answerCount = answerCount + 1
	WHERE postID = ${postID}"
);

echo json_encode($result);
exit;