<?php   
    $connection = new SqlConnection();

    $filter = "";

    if(!empty($_REQUEST["query"])) {
        $query = $_REQUEST["query"];
        $filter .= "
            WHERE courseName COLLATE UTF8_GENERAL_CI LIKE '%${query}%'
        ";
    } else {
        echo "
            <div class='no-results'>
                <h1><i class='fa fa-search'></i></h1>
                <p>Your search results will appear here.</p>
            </div>
        ";
        exit;
    }

    $connection->query("
    	SELECT *
        FROM Courses INNER JOIN Departments USING (departmentID)
        ${filter}
        ORDER BY 
        CASE WHEN courseName COLLATE UTF8_GENERAL_CI LIKE '${query}' THEN 5 ELSE 0 END
        + CASE WHEN courseName COLLATE UTF8_GENERAL_CI LIKE '${query}%' THEN 3 ELSE 0 END
        DESC, courseName ASC
        LIMIT 20
    ");

    if($connection->numRows() > 0) {
        while($row = $connection->fetchArray()) {
            extract($row);
            echo "
            <button class='course-suggestion btn btn-default' data-course='${courseID}'>
                <div class='course-name'>${courseName}</div>
                <div class='department-name'>${departmentName}</div>
            </button>
            <div class='clearfix'></div>
            ";
        }
    } else {
        echo "
            <div class='no-results'>
                <h1><i class='fa fa-search'></i></h1>
                <p>No courses named \"${query}\"</p>
            </div>
        ";
    }

?>