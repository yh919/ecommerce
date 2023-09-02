<?php 

// Title Function v 1.0
// Page Title in case the page
// has the variable $pageTitle and echo default Title for other pages


function getTitle () {

    global $pageTitle;

    if (isset($pageTitle)) {

        echo $pageTitle;
    } else {
        echo 'Default';
    }

}


// Redirect Function v 1.0
// This Function Used  For Incrupted Operations && Errors
// [This Function Accept Parameters]
// $errorMsg = echo 'Error Message'
// $seconds  = Redirect delay
// $url = Redirect Destination

function redirectHome($errorMsg, $seconds = 3,$url = 'index.php') {
    
    echo "<div class='alert alert-danger'>$errorMsg</div>";

    echo "<div class='alert alert-info'> You will be redirected to homepage after <strong> $seconds Seconds </strong></div>";

    header("Refresh:$seconds;url=$url");

    exit();
}

// Redirect Function v 1.0.1
// This Function Used For Successful Operations
// [This Function accept parameters]
// $seconds = Redirect delay
// $url == Redirect Destination
// $url default redirect to home page // Set $url value as you need from the function

function redirectSuccess($seconds,$url) {

    $seconds = 3;
    global $urlback;
    $urlback = $_SERVER['HTTP_REFERER'];

    echo "<div class='alert alert-info'> You will be redirected after <strong> $seconds Seconds </strong></div>";

    header("Refresh:$seconds;url=$url");

    exit();
}

//

// function redirectBack ($seconds = 3) {
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
// }



// Check items function v 1.0
// Check items @ Database Function
// [Function accept parameters]
// $select = The item to select [Example: user, item, category]
// $from = Table to select from [Example : users, items, categories]
// $value = Value of select [Example : bunny, box, electronics]

function checkItem($select, $from, $value) {

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statement->execute(array($value));

    $count = $statement->rowCount();

    return $count;

}


// Count number of items Function v 1.0
// Function to count number of Rows
// $item = Item You Will Check For
// $table = Table you will check in

function countItems($item, $table) {

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}


// Get Latest Records Function V 1.0
// Function to get latest itemds from database [Users , Items , comments]
// $select = Field to select
// $table = Table to choose from
// $limit = number of records to get
function getLatest($select, $table, $order ,$limit = 5){

    global $con;

    $getStmt = $con->prepare("SELECT $select from $table ORDER BY $order DESC LIMIT $limit");

    $getStmt->execute();

    $rows = $getStmt->fetchAll();

    return $rows;

}




?>