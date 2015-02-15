<?php

$connection = new SqlConnection();
$userID = $_SESSION["userID"];

if(!empty($_REQUEST["targetID"])) {
	$schedule = new Schedule($_REQUEST["targetID"]);
	$schedule->render();
}

?>