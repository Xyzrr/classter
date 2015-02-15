<?php   
    $offset = (int) $_REQUEST["offset"];
    $count = (int) $_REQUEST["count"];
    $db = new SqlConnection();

    $filter = "WHERE 1=1 ";

    if(!empty($_REQUEST["query"])) {
        $query = $db->escapeString($_REQUEST["query"]);
        $filter .= "
            AND (
                CONCAT(firstName, ' ', lastName) COLLATE UTF8_GENERAL_CI LIKE '%${query}%'
                OR CONCAT(lastName, ', ', firstName) COLLATE UTF8_GENERAL_CI LIKE '%${query}%'
            )
        ";
    } else {
        $query = "";
    }

    if(!empty($_REQUEST["tab"])) {
        $tab = $db->escapeString($_REQUEST["tab"]);
        if($tab == "teachers") {
            $filter .= "AND teacherID IS NOT NULL";
        }
        if($tab == "students") {
            $filter .= "AND studentID IS NOT NULL";
        }
    }

    $db->query(
    	"SELECT *, Users.userID AS userID
    	FROM Users
        LEFT JOIN Students USING (userID)
        LEFT JOIN Teachers USING (userID)
        LEFT JOIN Admins USING (userID)
        LEFT JOIN Thumbnails USING (thumbnailID)
        ${filter}
        ORDER BY 
        CASE WHEN firstName COLLATE UTF8_GENERAL_CI LIKE '${query}' THEN 5 ELSE 0 END
        + CASE WHEN firstName COLLATE UTF8_GENERAL_CI LIKE '${query}%' THEN 3 ELSE 0 END
        + CASE WHEN lastName COLLATE UTF8_GENERAL_CI LIKE '${query}' THEN 5 ELSE 0 END
        + CASE WHEN lastName COLLATE UTF8_GENERAL_CI LIKE '${query}%' THEN 3 ELSE 0 END
        DESC, lastName ASC
    	LIMIT ${offset}, ${count};
        ;
    ");
    
    if($db->numRows() > 0) {

        while($row = $db->fetchArray()) {
            Format::htmlspecialArray($row);
            extract($row);
            $role = Acadefly::getRole($row);

            $homeURL = $row["homeURL"];
            $person["externalLink"] =
            $homeURL ? "<a href='${homeURL}' class='btn btn-default link'><i class='fa fa-external-link'></i></a>" : "";
            $name = Acadefly::getName($row);
            
            echo "
            <div class='thumbnail-wrapper'>
                <a href='" . HOME . "/profile/?id=${userID}'>
                </a>
                <div class='image'>
                    <img src='" . Acadefly::getThumbnail($row, "medium") . "'/>
                </div>
                <div class='info'>
                    <span class='name'>${name}</span>
                    <span class='role'>${role}</span>
                </div>
            </div>
            ";
        }
    } else {
        if($offset == 0) {
            echo "
                <div class='no-results'>
                    <h1><i class='fa fa-search'></i></h1>
                    <p>No results for \"${query}\"</p>
                </div>
            ";
        }
    }