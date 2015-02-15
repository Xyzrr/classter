<?php

Acadefly::verifyUserIsLoggedIn();

$layout = new MainLayout("404.php");
$layout->render();