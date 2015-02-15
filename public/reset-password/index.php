<?php
    if(!empty($_GET["token"])) {
      $connection = new SqlConnection();

      $token = $_GET["token"];

      $connection->query("
      SELECT *
      FROM ResetPasswords
      WHERE 
      token = '${token}';
      ");

      if($connection->numRows() == 1) {
        $row = $connection->fetchArray();
        $userID = $row["userID"];
        $connection->query("
          SELECT *
          FROM Users
          WHERE userID = ${userID};
        ");
        $row = $connection->fetchArray();
        $email = $row["email"];
        $layout = new LoginLayout("reset-password.php", array("userID" => $userID, "email" => $email));
        $layout->render();
      }
    }

    if(!empty($_POST["set-new-password"])) {
      $userID = $_POST["userID"];
      $email = $_POST["email"];

      $layout = new LoginLayout("reset-password.php", array("userID" => $userID, "email" => $email));
      
      if(empty($_POST["password"])) {
        $layout->append("error", "Please enter your new password.");
        $layout->render();
      }
      if(empty($_POST["confirm-password"])) {
        $layout->append("error", "Please confirm your new password.");
        $layout->render();
      }
      if($_POST["password"] !== $_POST["confirm-password"]) {
        $layout->append("error", "Your two password entries don't match.");
        $layout->render();
      }

      $connection = new SqlConnection();

      $password = md5($connection->escapeString($_POST["password"]));
      $confirmPassword = md5($connection->escapeString($_POST["confirm-password"]));

      $connection->query("
      UPDATE Users
      SET password = '${password}'
      WHERE userID = ${userID};
      ");
      $connection->query("
      DELETE FROM ResetPasswords
      WHERE userID = ${userID};
      ");

      $layout = new LoginLayout("reset-password-message.php");
      $layout->render();
    }

    header("Location: ../forgot-password/");
    exit();
?>