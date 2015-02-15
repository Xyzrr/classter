<?php

$blockDay = Acadefly::getBlockDay("June 15, 2015");
error_log($blockDay["blockDayName"]);

$layout = new MainLayout("admin/edit-school-schedule.php");
$layout->render();