<?php

$db = new SqlConnection();
$userID = (int) $_SESSION["userID"];

$db->query("
	SELECT period, courseName, classID
	FROM Classes
	INNER JOIN UserClasses USING (classID)
	LEFT JOIN Courses ON Classes.courseID = Courses.courseID
	WHERE userID = ${userID}
");

$classOptions = "";
while($row = $db->fetchArray()) {
	extract($row);
	$classOptions .= "
	<option value='${classID}' data-subtext='Period ${period}'>${courseName}</option>
	";
}

$editor = Acadefly::getEditor();
$layout = new MainLayout("ask-question.php", array("classOptions" => $classOptions, "editor" => $editor, 
	"scripts" => array("hotkeys", "wysiwyg", "select", "ask-question")));

$layout->render();