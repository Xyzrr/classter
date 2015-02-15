<?php

$postID = (int) $_GET["postID"];
$currentUserID = $_SESSION["userID"];

$db = new SqlConnection();
$db->query(
    "SELECT courseID, userID AS posterID FROM Posts
    WHERE postID = ${postID}");
$row = $db->fetchArray();
$posterID = $row["posterID"];

$db->query(
    "SELECT *, Posts.creationDate AS postCreationDate
    FROM Posts
    LEFT JOIN (
        Users 
        LEFT JOIN Thumbnails USING (thumbnailID)
    ) ON Posts.userID = Users.userID
    LEFT JOIN Courses x ON Posts.courseID = x.courseID
    WHERE postID = ${postID}
");
$row = $db->fetchArray();
extract($row);
$relativeTime = Acadefly::timeToString($postCreationDate);
$postTitle = stripslashes($postTitle);
$postBody = stripslashes(strip_tags($postBody, "<b><i><strike><u><font><blockquote><a><img><ul><ol><span><div>"));
$userBox = Acadefly::getUserBox($row);
echo "
<div class='question-panel'>
    <div class='header'>
        <div class='rep-box' data-id='${postID}'>
        </div>
        <div class='info'>
            <h1>${postTitle}</h1>
            <span class='question-subject'>${courseName}</span><span class='time'> - ${relativeTime} ago</span>
            ";
if($currentUserID == $posterID) {
    echo "      <a class='post-button delete-post' data=id='${postID}'><i>&times;</i></a>
                <a class='post-button edit-post' data=id='${postID}'><i class='fa fa-pencil'></i></a>
    ";
}

echo "      <div class='bottom-right'>
                ${userBox}
            </div>
        </div>
    </div>
    <div class='body'>
        <p>${postBody}</p>
    </div>
    <div class='comment-box' data-id='${postID}'>
    </div>
</div>";