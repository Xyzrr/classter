<?php
function echoActiveIfAt($str) {
    $uri = $_SERVER["REQUEST_URI"];

    if(strpos($uri, $str) !== false) {
        echo " class = 'active'";
    }
}
?>

<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li<?= echoActiveIfAt("dashboard") ?>><a href="<?= HOME ?>/dashboard/"><i class="fa fa-dashboard"></i> Dashboard</a>
        <li<?= echoActiveIfAt("profile") ?>><a href="<?= HOME ?>/profile/"><i class="fa fa-user"></i> Profile</a>
        </li>
        <li<?= echoActiveIfAt("calendar") ?>><a href="<?= HOME ?>/calendar/"><i class="fa fa-calendar"></i> Calendar</a>
        <li<?= echoActiveIfAt("pages") ?>><a href="<?= HOME ?>/pages/"><i class="fa fa-file"></i> Pages</a>
        </li>
        <!-- <li><a href="<?= HOME ?>/suggestions/"><i class="fa fa-lightbulb-o"></i> Suggestions</a> -->
        <li<?= echoActiveIfAt("question") ?>><a href="<?= HOME ?>/questions/"><i class="fa fa-question"></i> Questions</a>
        </li>
        <li<?= echoActiveIfAt("people") ?>><a href="<?= HOME ?>/people/"><i class="fa fa-users"></i> People</a>
        </li>
    </ul>
</div>