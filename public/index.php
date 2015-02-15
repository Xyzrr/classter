<?php
  if(!empty($_REQUEST['logout'])) {

    //Clear session
    $_SESSION = array();
    session_destroy();

    //Clear login cookie
    if(isset($_COOKIE["login_token"])) {
      $db = new SqlConnection();

      $token = $db->escapeString($_COOKIE["login_token"]);
      $db->query(
        "DELETE FROM LoginTokens
        WHERE token = '${token}'
      ");
      setcookie("login_token", "", time()-3600, "/");
    }

    header("Location: .");
    exit();
  }

  if(!empty($_SESSION["userID"]) && !empty($_SESSION["user-is-logged-in"]) && $_SESSION["user-is-logged-in"]) {
  	header("Location: dashboard/");
    exit;
  } elseif(isset($_COOKIE["login_token"])) {
    if(Acadefly::loginWithCookie()) {
      header("Location: dashboard/");
      exit;
    }
  }

	$layout = new HomeLayout("home.php", array("scripts" => array("home")));
	$layout->render();
?>