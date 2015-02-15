<?php

$db = new SqlConnection();
$targetID = $_GET["targetID"];
$userID = $_SESSION["userID"];

$db->query("
    SELECT *, Users.userID AS userID
    FROM Users
    WHERE Users.userID = ${userID};
");

?>