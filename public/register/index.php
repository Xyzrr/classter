<?php
  
  	if(!empty($_POST["registered"])) {
      $errorChecker = new ErrorChecker(new LoginLayout("register.php"), array("firstName", "lastName", "email", "password"));

      if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorChecker->writeError("Please enter a valid email address.");
      }

      $connection = new SqlConnection();

      $firstName = $connection->escapeString($_POST['firstName']);
      $lastName = $connection->escapeString($_POST['lastName']);
	    $password = md5($connection->escapeString($_POST['password']));
	    $email = $connection->escapeString($_POST['email']);

      $connection->query("
      SELECT * 
      FROM Users 
      WHERE email = '${email}';
      ");

      if($connection->numRows() == 1) {
        $errorChecker->writeError("That email's already taken. Are you trying to <a href='../login/'>log in?</a>");
      }

      $connection->query("
      INSERT INTO Users SET
      firstName = '${firstName}',
      lastName = '${lastName}',
      password = '${password}',
      email = '${email}';
      ");

      $lastID = $connection->lastID();

      $connection->query("
        INSERT INTO Students SET
        userID = '${lastID}';
      ");

      $_SESSION['userID'] = $lastID;
      $_SESSION['loggedIn'] = 1;
      header("Location: ../profile/");
      exit ();
	}
    $layout = new LoginLayout("register.php");
  	$layout->render();

?>