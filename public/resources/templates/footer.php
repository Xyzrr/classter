<?php require_once ROOT . "/js/config.js.php" ?>

<script src="<?= HOME ?>/js/pace.min.js"></script>
<script src="<?= HOME ?>/js/acadefly-min.js"></script>


<?php
if(!empty($scripts)) {
	$modules = array(
		"wysiwyg" => "bootstrap-wysiwyg-min",
		"questions" => "questions-min",
		"question" => "question-min",
		"pages" => "pages-min",
		"profile" => "profile-min",
		"people" => "people-min",
		"charts" => "Chart-min",
		"ask-question" => "ask-question-min",
		"hotkeys" => "jquery.hotkeys-min",
		"schedule" => "schedule-min",
		"footable" => "footable.min",
		"tooltipster" => "jquery.tooltipster-min",
		"select" => "bootstrap-select-min",
		"masonry" => "masonry.pkgd-min",
		"pagination" => "pagination-min",
		"images-loaded" => "imagesloaded.pkgd-min",
		"homeNavbar" => "home-navbar-min",
		"calendario" => "jquery.calendario-min",
		"calendar" => "calendar-min",
		"dashboard" => "dashboard-min",
		"collapsible" => "collapsible-min",
		"forgot-password" => "forgot-password-min",
		"infinite-scroll" => "infinite-scroll-min",
		"validate" => "jquery.validate-min",
		"editable" => "editable-min",
		"home" => "home-min"
		);
	foreach($scripts as $script) {
		echo "<script src='" . HOME . "/js/" . $modules[$script] . ".js'></script>";
	}
}