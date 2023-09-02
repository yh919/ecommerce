<?php 

include 'connect.php';

// Routes

$tpl        = 'includes/templ/'; // Templates Directory
$incl_lang  = 'includes/languages/'; // Include/Languages Directory
$func       = 'includes/functions/'; // Functions
$incl       = 'includes/'; // Include Directory
$css        = 'layout/css/'; // CSS Directory
$js         = 'layout/js/'; // JavaScript Directory


// include importants
include $func . 'functions.php';
include $incl_lang . 'en.php';
include $tpl . 'header.php';

// Include navbar on all pages expect $noNavbar Variable pages

if (!isset($noNavbar)) {include $tpl . 'navbar.php';}


?>