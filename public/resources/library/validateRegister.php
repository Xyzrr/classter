<?php

  function writeError($error) {
    echo "<div class='alert alert-danger' role-'alert'>" . $error . "</div>";
    exit ();
  }

  if(!empty($_GET['email']) and !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    writeError("Please enter a valid email address.");
  }

  $connection = new SqlConnection();

  $email = $connection->escapeString($_GET["email"]);

  $connection->query("
  SELECT * 
  FROM Users 
  WHERE email = '${email}';
  ");

  if($connection->numRows() == 1) {
    writeError("That email's already taken. Are you trying to <a href='../login/'>log in?</a>");
  }
  
?>