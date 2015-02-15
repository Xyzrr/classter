<?php

$uri = $_SERVER["REQUEST_URI"];
$location = "";
if(strpos($uri, "profile") !== false) {
    $location = "Profile";
}
if(strpos($uri, "questions") !== false) {
    $location = "Questions";
}
if(strpos($uri, "people") !== false) {
    $location = "People";
}
if(strpos($uri, "pages") !== false) {
    $location = "Pages";
}
if(strpos($uri, "dashboard") !== false) {
    $location = "Dashboard";
}
if(strpos($uri, "calendar") !== false) {
    $location = "Calendar";
}
if(strpos($uri, "classes") !== false) {
    $location = "Classes";
}

?>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- <input type="search" class="form-control search-all"/> -->
    <div class="input-group search-all">
      <input type="text" class="form-control" placeholder="Search for people">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button"><i class='fa fa-search'></i></button>
      </span>
    </div><!-- /input-group -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a id="menu-toggle" href="#" class="btn btn-default">
      <span class="sr-only">Toggle navigation</span>
      <i class="fa fa-angle-left"></i><span class="current-location"><?= $location ?></span>
    </a>
      <!-- <a class="navbar-brand" href="<?= HOME ?>/">Acadefly</a> -->
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="visible-xs-inline"><a href="<?= HOME ?>/profile/">Profile</a></li>
        <li class="visible-xs-inline"><a href="<?= HOME ?>/?logout=1">Log Out</a></li>
        <li class="dropdown hidden-xs user-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $_SESSION["userName"] ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?= HOME ?>/profile/">Profile</a></li>
            <li><a href="<?= HOME ?>/?logout=1">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>