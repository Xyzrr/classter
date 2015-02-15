<?php
    if(!empty($_POST["reset-password"])) {
      if(empty($_POST["email"])) {
        $error = "Please enter your email.";
        $layout = new LoginLayout("forgot-password.php", array("error" => $error));
        $layout->render();
        exit();
      }

      $connection = new SqlConnection();

      $email = $connection->escapeString($_POST["email"]);

      $connection->query("
      SELECT *
      FROM Users
      WHERE email = '$email';
      ");

      if($connection->numRows() == 1) {

        $row = $connection->fetchArray();
        $userID = $row["userID"];
        $token = md5($_POST['email'] . time());

        $connection->query("
        INSERT INTO ResetPasswords SET
        userID = '${userID}',
        token = '${token}';
        ");

        $subject = "Acadefly Password Reset";
        $message = "Forgot your password? Reset it by clicking <a href='http://www.nexos.comlu.com/reset-password/?token=${token}'>here</a>.";
        $headers = 'From: Acadefly Support' . "\r\n" .
        'Reply-To: no-reply@acadefly.com' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($email, $subject, $message, $headers);
        $layout = new LoginLayout("forgot-password-message.php");
        $layout->render();
        exit ();
      } else {
        $error = "Email not found in our databases.";
        $layout = new LoginLayout("forgot-password.php", array("error" => $error));
        $layout->render();
        exit();
      }
    }

    $layout = new LoginLayout("forgot-password.php");
  	$layout->render();
?>