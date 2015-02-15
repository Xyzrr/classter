<?php

$userID = $_SESSION["userID"];

$db = new SqlConnection();

extract($_REQUEST);

if($tab == "#school" or $tab == "#world") {
    $db->query(
        "SELECT SQL_CALC_FOUND_ROWS 
                *, Posts.creationDate AS postCreationDate
        FROM Posts
        LEFT JOIN (
            Users 
            LEFT JOIN Thumbnails USING (thumbnailID)
        ) USING (userID)
        LEFT JOIN Courses ON Posts.courseID = Courses.courseID
        WHERE Posts.courseID IS NOT NULL
        ORDER BY Posts.creationDate DESC
        LIMIT ${offset}, ${perPage}
    ");
}
if($tab == "#mine") {
    $db->query(
        "SELECT SQL_CALC_FOUND_ROWS 
                *, Posts.creationDate AS postCreationDate
        FROM Posts
        LEFT JOIN (
            Users 
            LEFT JOIN Thumbnails USING (thumbnailID)
        ) USING (userID)
        LEFT JOIN Courses ON Posts.courseID = Courses.courseID
        WHERE Posts.courseID IS NOT NULL
        AND userID = ${userID}
        ORDER BY Posts.creationDate DESC
        LIMIT ${offset}, ${perPage}
    ");
}

if($db->numRows() == 0) {
    ob_start();
    require_once TEMPLATES_PATH . "/errors/no-questions.php";
    $result["questions"] = ob_get_contents();
    ob_end_clean();
    $result["postCount"] = 0;
    echo json_encode($result);
    exit();
}

$result["questions"] = "";
while($row = $db->fetchArray()) {
	extract($row);
    $userBox = Acadefly::getUserBox($row, "top", "right");
    $relativeTime = Acadefly::timeToString($postCreationDate);
    $maxLength = 200;
    $excerpt = substr(stripslashes(strip_tags($postBody)), 0, $maxLength);
    if(strlen($postBody) > $maxLength) {
        $excerpt .= " &#133;"; //Append ellipses
    }

    $postScoreClass = "";
    $postScoreIcon = "fa-arrow-up";
    if($postScore > 0)
        $postScoreClass = "positive";
    if($postScore < 0) {
        $postScoreClass = "negative";
        $postScoreIcon = "fa-arrow-down";
    }
    $answerCountClass = "";
    if($answerCount > 0)
        $answerCountClass = "positive";
	$result["questions"] .= "
    <div class='question-summary'>
        <a class='mobile-link visible-xs-block' href='?id=${postID}'></a>
        <div class='info'>
            <table class='stats table'>
                <tr data-toggle='tooltip' data-placement='right' data-container='body'
                    title='${postScore} votes'>
                    <td class='post-score ${postScoreClass}'>${postScore}</td>
                    <td><i class='fa ${postScoreIcon}'></i></td>
                </tr>
                <tr data-toggle='tooltip' data-placement='right' data-container='body'
                    title='${answerCount} answers'>
                    <td class='answer-count ${answerCountClass}'>${answerCount}</td>
                    <td><i class='fa fa-comment'></i></td>
                </tr>
                <tr data-toggle='tooltip' data-placement='right' data-container='body'
                    title='${viewCount} views'>
                    <td class='views'>${viewCount}</td>
                    <td><i class='fa fa-eye'></i></td>
                </tr>
            </table>
        </div>
        <div class='main'>
            <div class='header'>
                <span class='subject'>${courseName}</span><span class='time'> - ${relativeTime} ago</span>
                <div class='title'>
                    <a href='?id=${postID}'>${postTitle}</a>
                </div>
                <div class='bottom-right hidden-xs'>
                    ${userBox}
                </div>
            </div>
            <div class='body'>${excerpt}
            </div>
        </div>
    </div>
	";
}

$db->query(
    "SELECT FOUND_ROWS() AS postCount;
");
$row = $db->fetchArray();
$result["postCount"] = $row["postCount"];

echo json_encode($result);