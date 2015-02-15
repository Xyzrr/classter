<?php

extract($_REQUEST);
extract($_SESSION);

$result = array();
$result["success"] = true;

$db = new SqlConnection();
$db->query(
	"SELECT userID AS commenterID FROM Posts
	INNER JOIN Users USING (userID)
	WHERE postID = ${postID}
");
extract($db->fetchArray());
if($userID != $commenterID) {
	$result["success"] = false;
	$result["error"] = "You can only delete your own posts.";
}

if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

$db->query(
	"DELETE FROM Posts
	WHERE postID = ${postID}");

echo json_encode($result);