<!-- Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats ] -->

<!-- Condition ? True : False -->

<?php

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if (isset($_GET['do'])) {
    $do = $_GET['do'];
} else {
    $do = 'Manage';
}

// echo $do;

// If the page is main page

if ($do == 'Manage' ) {
    echo "Welcome you are in manage category page";
} elseif($do == 'Add') {
    echo ' Welcome you are in Add Category page';
} elseif ($do == 'Insert ') {
    echo ' Welcome you are in Insert Category page';

} else {
    echo 'ERROR 404';
}