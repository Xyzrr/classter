<?php   
    $connection = new SqlConnection();
    
    $filter = "";

    if(!empty($_REQUEST["period"])) {
        $per = $_REQUEST["period"];
    }

    if(!empty($_REQUEST["query"])) {
        $query = $_REQUEST["query"];
        $filter .= 
            "WHERE CONCAT(firstName, ' ', lastName) COLLATE UTF8_GENERAL_CI LIKE '%${query}%'
            OR CONCAT(lastName, ', ', firstName) COLLATE UTF8_GENERAL_CI LIKE '%${query}%'
        ";
    } else {
        echo "
            <div class='no-results'>
                <h1><i class='fa fa-search'></i></h1>
                <p>Your search results will appear here.</p>
            </div>";
        exit;
    }

    $connection->query(
    	"SELECT *, 
        COUNT(Classes.classID) AS numClasses,
        Teachers.userID AS teacherUserID,
        Classes.classID AS classID
        FROM Users
        LEFT JOIN (
            UserClasses 
            INNER JOIN (
                Classes
                LEFT JOIN Courses USING (courseID)
                LEFT JOIN CourseLevels USING (courseLevelID)
            ) ON Classes.classID = UserClasses.classID AND period = ${per}
        ) USING (userID)
        INNER JOIN Teachers USING (userID)
        LEFT JOIN Thumbnails USING (thumbnailID)
        ${filter}
        GROUP BY userID
        ORDER BY 
        CASE WHEN firstName COLLATE UTF8_GENERAL_CI LIKE '${query}' THEN 4 ELSE 0 END
        + CASE WHEN firstName COLLATE UTF8_GENERAL_CI LIKE '${query}%' THEN 2 ELSE 0 END
        + CASE WHEN lastName COLLATE UTF8_GENERAL_CI LIKE '${query}' THEN 5 ELSE 0 END
        + CASE WHEN lastName COLLATE UTF8_GENERAL_CI LIKE '${query}%' THEN 3 ELSE 0 END
        DESC, lastName ASC
        LIMIT 12
    ");

    if($connection->numRows() > 0) {

        while($row = $connection->fetchArray()) {
            extract($row);
            $teacherName = Acadefly::getName($row);
            $thumbnail = Acadefly::getThumbnail($row, "medium");
            if(empty($courseName)) {
                $courseName = "Unnamed course";
            }
            if(empty($courseLevelName)) {
                $courseLevelName = "";
            }

            echo "
            <div class='thumbnail-wrapper' data-teacher='${teacherUserID}'>
                <div class='image'>
                    <img src='${thumbnail}'/>
                </div>
                <div class='info'>
                    <span class='name'>${teacherName}</span>
            ";
            if($numClasses > 0) {
                $suffix = $studentCount==1 ? "" : "s";
                echo "
                    <span class='course-name'>${courseName} ${courseLevelName}</span>
                    <span class='num-students'>${studentCount} Student${suffix}</span>
                ";
            } else {
                echo "
                    <span class='course-name'>Create Class</span>
                ";
            }
            echo "
                </div>
            </div>
            ";
        }

    } else {

        echo "
            <div class='no-results'>
                <h1><i class='fa fa-search'></i></h1>
                <p>No teachers named \"${query}\"</p>
            </div>
        ";

    }
?>