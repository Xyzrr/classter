<?php

extract($_REQUEST);
extract($_SESSION);

$db = new SqlConnection();
$db->query(
	"SELECT postTypeID, userID FROM Posts
	WHERE postID = ${postID}
	");
$row = $db->fetchArray();
$posterID = $row["userID"];

$result["success"] = true;

if($userID == $posterID) {
	$result["success"] = false;
	$result["error"] = "You cannot vote for your own posts.";
}
if(!$result["success"]) {
	echo json_encode($result);
	exit;
}

if($row["postTypeID"] == 1) {
	$voteUpValue = $conf["vote_values"]["question_up"];
	$voteDownValue = $conf["vote_values"]["question_down"];
} else {
	$voteUpValue = $conf["vote_values"]["answer_up"];
	$voteDownValue = $conf["vote_values"]["answer_down"];
}
$db->query(
	"SELECT voteID, voteTypeID FROM Votes
	WHERE userID = ${userID}
	AND postID = ${postID}
	");
if($row = $db->fetchArray()) {
	extract($row);
	$voteType = $voteTypeID == 1 ? "up" : "down";

	$db->query(
		"DELETE FROM Votes
		WHERE voteID = ${voteID}
		");

	if($direction == "up") {
		if($voteType == "up") {
			$scoreChange = -1;
			$repChange = -$voteUpValue;
		} else {
			$scoreChange = 2;
			$repChange = -$voteDownValue + $voteUpValue;
			$newVoteType = "up";
		}
	} else {
		if($voteType == "up") {
			$scoreChange = -2;
			$repChange = -$voteUpValue + $voteDownValue;
			$newVoteType = "down";
		} else {
			$scoreChange = 1;
			$repChange = -$voteDownValue;
		}
	}

} else {
	if($direction == "up") {
		$scoreChange = 1;
		$newVoteType = "up";
		$repChange = $voteUpValue;

	} else {
		$scoreChange = -1;
		$newVoteType = "down";
		$repChange = $voteDownValue;
	}
}

if(!empty($newVoteType)) {
	$result["newVote"] = $newVoteType;
	$newVoteTypeID = $newVoteType == "up" ? 1 : 2;
	$db->query(
		"INSERT INTO Votes SET
		userID = ${userID},
		postID = ${postID},
		voteTypeID = ${newVoteTypeID}
	");
}

$db->query(
	"UPDATE Users
	SET reputation = reputation + ${repChange}
	WHERE userID = ${posterID}
");

$result["scoreChange"] = $scoreChange;
$db->query(
	"UPDATE Posts
	SET postScore = postScore + ${scoreChange}
	WHERE postID = ${postID}
");

echo json_encode($result);
exit;