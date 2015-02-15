<?php

  extract($_REQUEST);
  extract($_SESSION);

  $result = array("success" => true);

  if(empty($email)) {
    $result['success'] = false;
    $result['email'] = "Please enter an email.";
  } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result['success'] = false;
    $result['email'] = "Invalid email format.";
  }

  if(!$result["success"]) {
    echo json_encode($result);
    exit;
  }

  $db = new SqlConnection();

  $email = $db->escapeString($email);

  $db->query(
  "SELECT *
  FROM Users
  WHERE email = '${email}';
  ");

  if($db->numRows() == 1) {

    $row = $db->fetchArray();
    $userID = $row["userID"];
    $token = $db->escapeString(md5($email . time()));

    $db->query(
    "INSERT INTO ResetPasswords SET
    userID = '${userID}',
    token = '${token}';
    ");

    $subject = "Acadefly Password Reset";
    $message = "Forgot your password? Reset it by clicking <a href='http://www.acadefly.com/forgot-password/reset-password/?t=${token}&id=${userID}'>here</a>.";
    $headers = 'From: Acadefly Support' . "\r\n" .
    'Reply-To: no-reply@acadefly.com' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($email, $subject, $message, $headers);

  } else {
    $result["success"] = false;
    $result['email'] = "Invalid email.";
  }

  echo json_encode($result);