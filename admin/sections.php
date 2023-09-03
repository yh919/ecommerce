<?php 

ob_start();
session_start();

include 'init.php';

$pageTitle = 'Sections'

?>


<?php 

include $tpl . 'footer.php';

ob_end_flush() ?>