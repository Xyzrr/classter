<?php

$postID = $_GET["postID"];
$currentUserID = $_SESSION["userID"];

$db = new SqlConnection();

$db->query(
	"SELECT *, userID AS posterID, Posts.creationDate AS postCreationDate FROM Posts
	LEFT JOIN (
		Users
		LEFT JOIN Students USING (userID)
		LEFT JOIN Teachers USING (userID)
        LEFT JOIN Thumbnails USING (thumbnailID)
	) USING (userID)
	WHERE parentID = ${postID}
    ORDER BY postScore DESC, Posts.creationDate ASC
	");

$answerCount = $db->numRows();
echo "
    <div class='number-of-answers'>
    ${answerCount} Answers
    </div>
";

while($row = $db->fetchArray()) {
	extract($row);
    $postBody = stripslashes(strip_tags($postBody, "<b><i><strike><u><font><blockquote><a><img><ul><ol><span><div>"));
    $userBox = Acadefly::getUserBox($row);
    $relativeTime = Acadefly::timeToString($postCreationDate);
	echo "
	<div class='answer-panel' data-id=${postID}>
        <div class='rep-box' data-id='${postID}'>
        </div>
        <div class='main'>
            <div class='body'>${postBody}
            ";
if($currentUserID == $posterID) {
    echo "      <a class='post-button delete-post'><i>&times;</i></a>
                <a class='post-button edit-post'><i class='fa fa-pencil'></i></a>
    ";
}
            echo "
            <div class='answer-time'>${relativeTime} ago
            </div>
            <div class='bottom-right'>
                    ${userBox}
                </div>
            </div>
            <div class='comment-box' data-id=${postID}>
            </div>
        </div>
    </div>
    ";
   }