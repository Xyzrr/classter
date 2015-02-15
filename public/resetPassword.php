<?php

extract($_POST);

$result = array("success" => true);

if(empty($password)) {
  $result["password"] = "Please enter your new password.";
  $result["success"] = false;
}
if(strlen($password) < 6) {
  $result["password"] = "Your password must be at least 6 characters.";
  $result["success"] = false;
}
if(empty($confirmPassword)) {
  $result["confirmPassword"] = "Please confirm your new password.";
  $result["success"] = false;
}
if($password !== $confirmPassword) {
  $result["confirmPassword"] = "Both passwords must match.";
  $result["success"] = false;
}

if(!$result["success"]) {
  echo json_encode($result);
  exit();
}

$connection = new SqlConnection();

$password = md5($connection->escapeString($password));
$userID = (int) $userID;

$connection->query(
"UPDATE Users
SET password = '${password}'
WHERE userID = ${userID};
");
$connection->query(
"DELETE FROM ResetPasswords
WHERE userID = ${userID};
");

echo json_encode($result);