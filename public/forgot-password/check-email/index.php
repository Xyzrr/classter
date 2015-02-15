<?php
	extract($_GET);
    $layout = new HomeLayout("forgot-password/check-email.php", array("email" => $email, "scripts" => array("forgot-password")));
  	$layout->render();