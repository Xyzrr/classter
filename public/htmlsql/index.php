<?php
    
    require_once(LIBRARY_PATH . "/htmlsql/snoopy.class.php");
    require_once(LIBRARY_PATH . "/htmlsql/htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    if (!$wsql->connect('file', 'teacher-pages.html')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    if (!$wsql->query('SELECT * FROM a WHERE $class == "sw-directory-item"')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    insertTeachersIntoDatabase($wsql);

    function printTeacherTable ($wsql) {
    	$output = "<div class='container'>";
    	$output .= "<table class='table'>";
    	$output .= "
    	<tr><th>First Name</th>
    	<th>Last Name</th>
    	<th>Homepage URL</th>
    	<th>Email</th></tr>";

    	foreach($wsql->fetch_array() as $row) {
	    	$name = explode(', ', $row['text']);
	        $email = strtolower($name[1][0] . $name[0]) . "@livingston.org" . "</p>";
	    	$output .= "<tr>";
	    	$output .= "<td>" . $name[1] . "</td>";
	        $output .= "<td>" . $name[0] . "</td>";
	        $output .= "<td>" . $row['href'] . "</td>";
	        $output .= "<td>" . $email . "</td>";
	        $output .= "</tr>";
	    }
        $output .= "</table></div>";

        $layout = new BasicLayout($output);
        $layout->render();
    }

    function insertTeachersIntoDatabase($wsql) {
    	$connection = new SqlConnection();
    	foreach($wsql->fetch_array() as $row) {
	    	$name = explode(', ', $row['text']);
	    	$firstName = $connection->escapeString($name[1]);
	    	$lastName = $connection->escapeString($name[0]);
	        $email = $connection->escapeString(strtolower($name[1][0] . $name[0]) . "@livingston.org");
	        $homeURL = $connection->escapeString($row["href"]);

	        $connection->query("
	        	INSERT INTO Users SET
	        	firstName = '${firstName}',
	        	lastName = '${lastName}',
	        	email = '${email}';
	        ");
	        $lastID = $connection->lastID();
	        $connection->query("
	        	INSERT INTO Teachers SET
	        	userID = ${lastID},
				homeURL = '${homeURL}';
	        ");
	    }
    }

    function printDepartmentTable ($wsql) {
        $output = "<div class='container'>";
        $output .= "<table class='table'>";
        $output .= "
        <tr><th>Name</th>
        <th>URL</th>
        ";

        foreach($wsql->fetch_array() as $row) {
            $output .= "<tr>";
            $output .= "<td>" . $row['text'] . "</td>";
            $output .= "<td>" . $row['href'] . "</td>";
            $output .= "</tr>";
        }
        $output .= "</table></div>";

        $layout = new BasicLayout($output);
        $layout->render();
    }

    function insertDepartmentsIntoDatabase($wsql) {
        $connection = new SqlConnection();
        foreach($wsql->fetch_array() as $row) {
            $name = $connection->escapeString($row["text"]);
            $departmentURL = $connection->escapeString($row["href"]);

            $connection->query("
                INSERT INTO Departments SET
                name = '${name}',
                departmentURL = '${departmentURL}';
            ");
        }
    }
?>