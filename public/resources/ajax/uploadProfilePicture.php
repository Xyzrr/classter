<?php

error_log("1");

$db = new SqlConnection();
$userID = $_SESSION["userID"];
$largeThumbnail = $db->escapeString("d" . $_POST["largeThumbnail"]);
$mediumThumbnail = $db->escapeString("d" . $_POST["mediumThumbnail"]);
$smallThumbnail = $db->escapeString("d" . $_POST["smallThumbnail"]);

error_log("2");

$db->query(
	"DELETE x FROM Thumbnails x
	INNER JOIN Users USING (thumbnailID)
	WHERE userID = ${userID}");
error_log("3");

$db->query(
	"INSERT INTO Thumbnails SET
	largeDataURL = '${largeThumbnail}',
	mediumDataURL = '${mediumThumbnail}',
	smallDataURL = '${smallThumbnail}'");
error_log("4");

$lastID = $db->lastID();
$db->query(
	"UPDATE Users SET
	thumbnailID = ${lastID}
	WHERE userID = ${userID}");
error_log("5");
error_log("Finished");
