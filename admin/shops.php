<?php
ob_start(); // Output Buffering Start
// Manage Members Page
// You can Add || Edit || Delete Mebers

session_start();

$pageTitle = 'Shops | Admin Panel';

if(isset($_SESSION['username'])) {

    // $pageTitle = 'Dashboard';

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') { // Manage categories Page

        $query = '';

        // Select Users from Database
        $stmt = $con->prepare("SELECT * FROM shops");
        // Excute Statement
        $stmt->execute();
        // Assign to variable
        $rows = $stmt->fetchAll();

        
        $stmt4 = $con->prepare("SELECT * FROM users");

        $stmt4->execute();

        $users = $stmt4->fetchAll();

    ?>

<h1 class="text-center"><?php echo lang('MANAGE_SHOPS') ?></h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Category Name</td>
                <td>Description</td>
                <td>Owner Name</td>
                <td>Registered Date</td>
                <td>Control</td>
            </tr>

            <?php

            foreach($rows as $row) {
                // $accessstatus = '';
                // if ($row['groupid'] == 1) {
                    // $accessstatus = lang('ADMIN');
                // } else {
                    // $accessstatus = lang('MEMBER');

                     $ownername = '';
                                             foreach($users as $ids) {
                                                if ($row['ownerid'] == $ids['userid']) {
                                                    $ownername = $ids['username'];
                                                }
                                            }

                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $ownername . "</td>";
                echo "<td>" . $row['regdate'] . "</td>";
                ?>
            <td>
                <a href="shops.php?do=Edit&id=<?php echo $row['id'] ?>" class="btn btn-success">
                    Edit</a>
                <a href="shops.php?do=Delete&id=<?php echo $row['id'] ?>" class="btn btn-danger confirm">
                    Delete</a>
                <?php
                    if ($row['active'] == 0) {
                        ?>
                <a href="shops.php?do=Activate&id=<?php echo $row['id'] ?>" class="btn btn-warning confirm">
                    Activate
                </a>
                <?php
                    } elseif ($row['active'] == 1) {
                        // ?>
                <a href="shops.php?do=Deactivate&id=<?php echo $row['id'] ?>" class="btn btn-warning confirm">
                    Deactive
                </a>
                <?php
                    }
                    ?>
            </td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <a class="btn btn-primary" href="?do=Add"> <i class="fa-solid fa-plus fa-xl"></i> New Category </a>
</div>


<?php } elseif ($do == 'Add') { // Add Members Page ?>

<h1 class="text-center">
    <?php echo lang('ADD_CATEGORY') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Insert" method="POST">
        <div class="col-md-4">
            <label for="username" class="form-label"><?php echo lang('CATEGORY_NAME')?></label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Shop Name">
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php  echo lang('CATEGORY_DESCTIPTION')?></label>
            <input type="text" class="form-control" id="description" name="description" required='required'
                placeholder="Enter Description">
        </div>
        <div class="col-md-4">
            <label for="shopowner" class="form-label"><?php echo lang('ITEM_CATEGORY') ?></label>
            <select name="shopowner" id="shopowner" class="form-select">
                <option selected> Choose Owner </option>
                <option value="1"> bunny </option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?php echo lang('SUBMIT') ?></button>
        </div>
    </form>
</div>



<?php

}elseif ($do == 'Insert') { // Insert Member Page

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

    echo "<h1 class='text-center'>";
    echo lang('UPDATE_MEMBER');
    echo "</h1>";
    echo "<div class='container'>";

        // Get Variables from FORM
        $shopname   = $_POST['name'];
        $descr      = $_POST['description'];
        $ownername  = $_POST['shopowner'];

        // Validate Form

        $formErrors = array();


        if (empty($shopname)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($descr)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        // If no error >> Update Operation

        if (empty($formErrors)) {

        // Check if user exist in database

        $check = checkItem("name", "categories", $shopname);


        if ($check == 1) {

            echo   "<div class='alert alert-warning'> <strong> Username $shopname already exist </strong> </div>";

            redirectSuccess(6,$url);

        }     else {

        // Insert info into database

        $stmt = $con->prepare("INSERT INTO
                                            shops (name, description, ownerid)
                                            VALUES (:zshopname, :zdescr, :zownerid)");
        $stmt->execute(array(
            'zshopname'       => $shopname,
            'zdescr'          => $descr,
            'zownerid'        => $ownername,
        ));
        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

        $url = 'shops.php?do=Manage';

        redirectSuccess(3,$url);

        }
    }
    } else {

        $errorMsg = "You Can't Access this page";

        $url = 'index.php';

        redirectHome($errorMsg, 6,$url);
    }

    echo "</div>";

}
 elseif ($do == 'Edit') { // Edit Page



        // Check IF Get user request is numeric & get the int value of it

        $shopid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        // Select All Data Depend on this ID
        $stmt = $con->prepare("SELECT * FROM shops WHERE id = ? LIMIT 1");

        // Execute Query
        $stmt->execute(array($shopid));

        // Fetch the data
        $row = $stmt->fetch();

        // The Row Count
        $count = $stmt->rowCount();


        // Group id variables
        // $usergroupid = '';
        // if ($row['groupid'] == '1') {
            // $usergroupid = lang('ADMIN');
        // } else {
            // $usergroupid = lang('MEMBER');
        // }

        // Truster Seller variables
        // $usertrusted = '';
        // if ($row['truststatus'] == '1') {
            // $usertrusted = lang('TRUSTED_SELLER');
        // } else {
            // $usertrusted = lang('NOT_TRUSTED_SELLER');
        // }
        // If ID is valid >> Show Form

            if ($stmt->rowCount() > 0 ) { ?>

<h1 class="text-center">
    <?php echo lang('EDIT_MEMBER') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Update" method="POST">
        <input type="hidden" name="id" value="<?php echo $shopid ?>">
        <div class="col-md-4">
            <label for="username" class="form-label"><?php echo lang('CATEGORY_NAME')?></label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Shop Name" value="<?php echo $row['name'] ?>">
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php  echo lang('CATEGORY_DESCTIPTION')?></label>
            <input type="text" class="form-control" id="description" name="description" required='required'
                placeholder="Enter Description" value="<?php echo $row['description'] ?>">
        </div>
        <div class="col-md-4">
            <label for="shopowner" class="form-label"><?php echo lang('ITEM_CATEGORY') ?></label>
            <select name="shopowner" id="shopowner" class="form-select">
                <option selected> Choose Owner </option>
                <option value="1"> bunny </option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?php echo lang('SUBMIT') ?></button>
        </div>
    </form>
</div>


<?php
                // If ID not Valid >> Show this message
                } else {

    $errorMsg = 'There is no such ID';
    $url = 'index.php';
    redirectHome($errorMsg, 3 , $url);
    }
} elseif ($do == 'Update') {

    echo "<h1 class='text-center'>";
    echo lang('UPDATE_MEMBER');
    echo "</h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // Get Variables from FORM

        $shopid     = $_POST['id'];
        $shopname   = $_POST['name'];
        $descr      = $_POST['description'];
        $ownername  = $_POST['shopowner'];



        // Validate Form
        
        $formErrors = array();

        if (empty($shopname)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($descr)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }


        // If no error >> Update Operation

        if (empty($formErrors)) {


        // Update Database with updated information

        $stmt = $con->prepare("UPDATE shops SET name = ? , description = ? , ownerid = ? WHERE id = ?");
        $stmt->execute(array($shopname,$descr,$ownername,$shopid));

        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

            $url = 'shops.php?do=Manage';
            $seconds = 3;
            redirectSuccess($seconds,$url);

        }
} else {

    $errorMsg = "You Can't Access this page";
    $url = 'index.php';
redirectHome($errorMsg, 5 ,$url);
}

echo "</div>";
} elseif ($do == 'Delete') { // Delete Users

echo "<h1 class='text-center'>";
    echo lang('DELETE_MEMBER');
    echo "</h1>";
echo "<div class='container'>";

    // Check IF Get user request is numeric & get the int value of it

    $shopid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM shops WHERE id = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($shopid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete User Depends on USERID
    $stmt = $con->prepare("DELETE FROM shops WHERE id = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($shopid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Deleted </div> ';

    $url = 'members.php?do=Manage';
    redirectSuccess(3,$url);

    } else {

    $errorMsg = 'This ID is not exist';
    $url = 'index.php';
    redirectHome($errorMsg,3,$url);
    }
    echo '</div>';
} elseif ($do == 'Activate') {

  echo "<h1 class='text-center'>";
    echo lang('ACTIVATE_MEMBER');
    echo "</h1>";
echo "<div class='container'>";

    // Check IF Get user request is numeric & get the int value of it

    $shopid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM shops WHERE id = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($shopid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Update  on Active ID
    $stmt = $con->prepare("UPDATE shops SET active = 1 WHERE id = ?");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($shopid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Activated </div> ';

    $url = 'shops.php?do=Manage';
    redirectSuccess(3,$url);

    } else {

    $errorMsg = 'This ID is not exist';
    $url = 'index.php';
    redirectHome($errorMsg,3,$url);
    }
    echo '</div>';


    
} elseif ($do == 'Deactivate') {
    echo "<h1 class='text-center'>";
    echo lang('ACTIVATE_MEMBER');
    echo "</h1>";
echo "<div class='container'>";

    // Check IF Get user request is numeric & get the int value of it

    $shopid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM shops WHERE id = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($shopid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete User Depends on USERID
    $stmt = $con->prepare("UPDATE shops SET active = 0 WHERE id = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($shopid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Dectivated </div> ';

    $url = '?do=Manage';
    redirectSuccess(3,$url);

    } else {

    $errorMsg = 'This ID is not exist';
    $url = $_SERVER['HTTP_REFFER'];
    redirectHome($errorMsg,3,$url);
    }
    echo '</div>';
} else {

    header('Location: index.php');

    exit();

}
}
?>

<?php include $tpl . 'footer.php'; ?>

<?

ob_end_flush();

?>