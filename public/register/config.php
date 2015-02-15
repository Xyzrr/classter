<?php
 
/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/
 
/*
    I will usually place the following in a bootstrap file or some type of environment
    setup file (code that is run at the start of every page request), but they work 
    just as well in your config file if it's in php (some alternatives to php are xml or ini files).
*/
 
/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/

if($_SERVER["DOCUMENT_ROOT"] == "/Applications/MAMP/htdocs")
    $isLocal = 1;
else
    $isLocal = 0;

$conf['vote_values'] = array(
    "answer_up" => 10,
    "answer_down" => -2,
    "question_up" => 5,
    "question_down" => -2,
    "review_up" => 10,
    "review_down" => -4,
    "voter_down" => -1,
);

if($isLocal == 1) {
    $conf['db_hostname'] = "localhost";
    $conf['db_password'] = "solidbreath1";
    $conf['db_name'] = 'db_nexos';
    $conf['db_username'] = 'root';

    define("ROOT", $_SERVER['DOCUMENT_ROOT'] . '/acadefly');
    define("HOME", "/acadefly");
    define("AJAX_PATH_CLIENT", HOME . "/resources/ajax");
    define("SECURE", false);
} else {
    $conf['db_hostname'] = "localhost";
    $conf['db_password'] = "solidbreath1";
    $conf['db_name'] = 'johnvqqa_johnqian_db';
    $conf['db_username'] = 'xyzrr';

    define("ROOT", '/home/johnvqqa/public_html/acadefly.com');
    define("HOME", "");
    define("AJAX_PATH_CLIENT", HOME . "/resources/ajax");
    define("SECURE", false);
}

define("IMAGES_PATH", ROOT . '/img');
define("LIBRARY_PATH", ROOT . '/resources/library');
define("TEMPLATES_PATH", ROOT . '/resources/templates');
define("AJAX_PATH", ROOT . '/resources/ajax');

function __autoload($class_name) {
    require_once LIBRARY_PATH . "/" . $class_name . '.php';
}
/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
 
?>