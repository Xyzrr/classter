<?php

if(!empty($_POST["count-students"])) {
		$db = new SqlConnection();
	    $db->query("
    	SELECT
        COUNT(userID) AS numStudents, classID
        FROM Classes LEFT JOIN UserClasses USING (classID)
        GROUP BY classID
        ");

	    $rows = $db->getResult();

        foreach($rows as $row) {
        	extract($row);
        	$db->query("
        		UPDATE Classes SET
        		studentCount = ${numStudents}
        		WHERE classID = ${classID}
	    		");
        }

        header("Location: .");
        exit();
}

if(!empty($_POST["count-answers"])) {
        $db = new SqlConnection();
        $db->query("
        SELECT
        COUNT(y.postID) AS numAnswers, x.postID AS postID
        FROM Posts x LEFT JOIN Posts y ON x.postID = y.parentID AND y.postTypeID = 2
        WHERE x.postTypeID = 1
        GROUP BY x.postID
        ");

        $rows = $db->getResult();

        foreach($rows as $row) {
            extract($row);
            $db->query("
                UPDATE Posts SET
                answerCount = ${numAnswers}
                WHERE postID = ${postID}
                ");
        }

        header("Location: .");
        exit();
}
if(!empty($_POST["update-user-data"])) {
        $db = new SqlConnection();
        $db->query("
        SELECT
        COUNT(postID) AS questionCount, userID
        FROM Users LEFT JOIN Posts USING (userID)
        WHERE postTypeID = 1
        GROUP BY Users.userID
        ");

        $rows = $db->getResult();

        foreach($rows as $row) {
            extract($row);
            $db->query("
                UPDATE Users SET
                questions = ${questionCount}
                WHERE userID = ${userID}
                ");
        }

        $db->query("
        SELECT
        COUNT(postID) AS answerCount, userID
        FROM Users LEFT JOIN Posts USING (userID)
        WHERE postTypeID = 2
        GROUP BY Users.userID
        ");

        $rows = $db->getResult();

        foreach($rows as $row) {
            extract($row);
            $db->query("
                UPDATE Users SET
                answers = ${answerCount}
                WHERE userID = ${userID}
                ");
        }

        header("Location: .");
        exit();
}

$layout = new MainLayout("admin/update-data.php");
$layout->render();