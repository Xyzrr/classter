<?php

Acadefly::verifyUserIsLoggedIn();

$db = new SqlConnection();
extract($_SESSION);

if(!empty($_GET["id"])) {
	$postID = (int) $_GET["id"];

	$db->query(
	"SELECT postID FROM Posts
	WHERE postID = ${postID}");

	if($db->numRows()) {

		$viewCounter = new ViewCounter();
		$viewCounter->addPostView($postID);

		$db->query(
			"SELECT userID FROM Posts
			WHERE postID = ${postID}
			AND userID = ${userID}
		");

		$answerBox = "";
		if($db->numRows() > 0) {
			$answerBox = '
			<button class="btn btn-default" id="show-answer-box">Answer your own question</button>
			';
		}

		$db->query(
			"SELECT userID FROM Posts
			WHERE parentID = ${postID}
			AND userID = ${userID}
		");
		if($db->numRows() > 0) {
			$answerBox = '
			<button class="btn btn-default" id="show-answer-box">Answer again</button>
			';
		}
		$layout = new MainLayout("question.php", array("postID" => $postID, "answerBox" => $answerBox, "scripts" => 
			array("hotkeys", "wysiwyg", "question")));
		$layout->render();
	} else {
		$layout = new MainLayout("errors/no-question.php");
		$layout->render();
	}
}

$db->query(
    "SELECT * FROM Users
    LEFT JOIN Students USING (userID)
    LEFT JOIN Teachers USING (userID)
    LEFT JOIN Thumbnails USING (thumbnailID)
    WHERE userID = ${userID}
");
$user = $db->fetchArray();
$user["profilePicture"] = Acadefly::getThumbnail($user, "medium");
$user["name"] = Acadefly::getName($user);
$db->query(
	"SELECT
	COUNT(CASE WHEN postTypeID = 1 THEN 1 ELSE NULL END) AS questionCount,
	COUNT(CASE WHEN postTypeID = 2 THEN 1 ELSE NULL END) AS answerCount
	FROM Posts
	WHERE userID = ${userID}
	");
$user = array_merge($user, $db->fetchArray());
$db->query(
	"SELECT
	COUNT(commentID) AS commentCount
	FROM Comments
	WHERE userID = ${userID}
	");
$user["scripts" ] = array("pagination", "questions");
$user = array_merge($user, $db->fetchArray());
$layout = new MainLayout("questions.php", $user);
$layout->render();