<?php
    if(!empty($_GET["id"])) {
      $classID = (int) $_GET["id"];
      $class = new Classroom($classID);
  	  $layout = new MainLayout("class.php", array("class" => $class));
  	  $layout->render();
    }

?>