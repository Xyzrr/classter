<?php
	Acadefly::verifyUserIsLoggedIn();

	$connection = new SqlConnection();
	$userID = $_SESSION["userID"];

	$connection->query(
	"SELECT *, Classes.homeworkURL AS classHomeworkURL
	FROM Classes
	LEFT JOIN Courses USING (courseID)
	INNER JOIN (
		UserClasses y INNER JOIN (
			Users INNER JOIN Teachers USING (userID)
		) USING (userID)
	) USING (classID)
	INNER JOIN UserClasses x ON Classes.classID = x.classID AND x.userID = ${userID}
	ORDER BY period
	");

	$tabs = "";
	$iframes = "";

	if($connection->numRows() > 0) {
		while($row = $connection->fetchArray()) {
			unset($homeworkURL);
			unset($courseName);
			extract($row);
			if(empty($courseName)) {
				$courseName = "Nameless";
			}
			if(!empty($classHomeworkURL)) {
				$homeworkURL = $classHomeworkURL;
			} else {
				$homeworkURL = $homeURL;
			}
            $tabs .= "
            <li>
            	<a class='page-tab' role='tab' href='#${period}'
            	data-toggle='tab' 
            	data-url='${homeworkURL}' 
            	data-class='${classID}'
            	data-home='${homeURL}'>
            		<span class='period hidden-sm'>${period}</span>
            		<span class='visible-md-inline visible-lg-inline'>&nbsp;&nbsp;</span>
            		<span class='name hidden-xs'>${courseName}</span>
            	</a>
        	</li>";

        	$iframes .= "
				<div class='tab-pane' id=${period}>
					<iframe src='${homeworkURL}' class='page-frame'>
						<p>Sorry, your browser does not support iFrames. Please consider switching to a more modern browser.</p>
					</iframe>
				</div>
        	";
		}
	  	$layout = new MainLayout("pages.php", array("tabs" => $tabs, "iframes" => $iframes, "scripts" => 
	  		array("pages")));
	  	$layout->render();
	} else {
		$layout = new MainLayout("pages-noclasses.php");
		$layout->render();
	}


?>