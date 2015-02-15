<?php
	Acadefly::verifyUserIsLoggedIn();

    $layout = new MainLayout("people.php", array("scripts" => array("infinite-scroll", "people")));
    $layout->render();
?>