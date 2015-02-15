<?php

if(!empty($_GET["t"]) && !empty($_GET["id"])) {
  $connection = new SqlConnection();

  $token = $_GET["t"];
  $userID = $_GET["id"];

  $connection->query("
  SELECT resetID
  FROM ResetPasswords
  WHERE 
  token = '${token}' AND userID = ${userID};
  ");

  if($connection->numRows() == 1) {
    $row = $connection->fetchArray();
    $connection->query("
      SELECT *
      FROM Users
      WHERE userID = ${userID};
    ");
    $row = $connection->fetchArray();
    $email = $row["email"];
    $layout = new HomeLayout("forgot-password/reset-password.php", array("userID" => $userID, "email" => $email, "scripts" => array("forgot-password")));
    $layout->render();
  } else {
    $layout = new HomeLayout("forgot-password/token-expired.php");
    $layout->render();
  }
} else {
  $layout = new HomeLayout("forgot-password/token-expired.php");
  $layout->render();
}