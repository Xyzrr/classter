<?php

extract($_REQUEST);
extract($_SESSION);

$result = array();
$result["success"] = true;

$db = new SqlConnection();
$db->query(
	"SELECT userID AS commenterID FROM Comments
	INNER JOIN Users USING (userID)
	WHERE commentID = ${commentID}
");
extract($db->fetchArray());
if($userID != $commenterID) {
	$result["success"] = false;
	$result["error"] = "You can only delete your own comments.";
}

if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

$db->query(
	"DELETE FROM Comments
	WHERE commentID = ${commentID}");

echo json_encode($result);