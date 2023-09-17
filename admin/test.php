<?php
ob_start(); // Output Buffering Start
// Manage categories Page
// You can Add || Edit || Delete Mebers

session_start();

$pageTitle = 'Categories | Admin Panel';

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
                ?>
            <td>
                <a href="categories.php?do=Edit&id=<?php echo $row['id'] ?>" class="btn btn-success">
                    Edit</a>
                <a href="categories.php?do=Delete&id=<?php echo $row['id'] ?>" class="btn btn-danger confirm">
                    Delete</a>
                <!-- <?php
                    // if ($row['regstatus'] == 0) {
                        ?>
                <a href="categories.php?do=Activate&userid=<?php // echo $row['userid'] ?>" class="btn btn-warning confirm">
                    Activate
                </a>
                <?php
                    // } elseif ($row['regstatus'] == 1) {
                        // ?>
                <a href="categories.php?do=Deactivate&userid=<?php // echo $row['userid'] ?>" class="btn btn-warning confirm">
                    Deactive
                </a> -->
                <?php
                    }
                    ?>
            </td>
            </tr>
            <?php
            ?>
        </table>
    </div>
    <a class="btn btn-primary" href="categories.php?do=Add"> <i class="fa-solid fa-plus fa-xl"></i> New Category </a>
</div>



<?php } elseif ($do == 'Add') { ?>



<h1 class="text-center">
    <?php echo lang('ADD_CATEGORY') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Insert" method="POST">
        <div class="col-md-4">
            <label for="username" class="form-label"><?php echo lang('CATEGORY_NAME')?></label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Category Name">
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php  echo lang('CATEGORY_DESCTIPTION')?></label>
            <input type="text" class="form-control" id="description" name="description" required='required'
                placeholder="Enter Description">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?php echo lang('SUBMIT') ?></button>
        </div>
    </form>
</div>
<?php } elseif ($do == 'Insert') { // Insert Member Page

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

    echo "<h1 class='text-center'>";
    echo lang('UPDATE_MEMBER');
    echo "</h1>";
    echo "<div class='container'>";

        // Get Variables from FORM

        $catname   = $_POST['name'];
        $descr   = $_POST['description'];

        // Validate Form

        $formErrors = array();


        if (empty($catname)) {

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

        $check = checkItem("name", "categories", $catname);


        if ($check == 1) {

            echo   "<div class='alert alert-warning'> <strong> Username $catname already exist </strong> </div>";

            redirectSuccess(6,$url);

        }     else {

        // Insert info into database

        $stmt = $con->prepare("INSERT INTO
                                            categories (name, description)
                                            VALUES (:zcatname, :zdescr)");
        $stmt->execute(array(
            'zcatname'        => $catname,
            'zdescr'        => $descr,
        ));
        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Added </div> ';

        $url = 'categories.php?do=Manage';
        
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

        $catid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        // Select All Data Depend on this ID
        $stmt = $con->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");

        // Execute Query
        $stmt->execute(array($catid));

        // Fetch the data
        $row = $stmt->fetch();

        // The Row Count
        $count = $stmt->rowCount();

        // If ID is valid >> Show Form

            if ($stmt->rowCount() > 0 ) { ?>

<h1 class="text-center">
    <?php echo lang('EDIT_CATEGORY') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Update" method="POST">
        <div class="col-md-4">
            <input type="hidden" name="catid" value="<?php echo $catid ?>">
            <label for="username" class="form-label"><?php echo lang('CATEGORY_NAME')?></label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Category Name" value="<?php echo $row['name'] ?>">
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php  echo lang('CATEGORY_DESCTIPTION')?></label>
            <input type="text" class="form-control" id="description" name="description" required='required'
                placeholder="Enter Description" value='<?php echo $row['description'] ?>'>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?php echo lang('UPDATETEXT') ?></button>
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

        $id         = $_POST['catid'];
        $catname    = $_POST['name'];
        $descr      = $_POST['description'];

       // 5od alcode mn ?do=Add


        // Validate Form

        $formErrors = array();

        // 5od alcode mn $do=Add
if (empty($catname)) {

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

        $check = checkItem("name", "categories", $catname);


        if ($check == 1) {

            echo   "<div class='alert alert-warning'> <strong> Username $catname already exist </strong> </div>";

            redirectSuccess(6,$url);

        }
        // Update Database with updated information


            //   متنساش تغير الباراميترز في الستاتمينت عشان متقعدش تدور علي الايرور فين ساعه
            //                                       متنساش

        $stmt = $con->prepare("UPDATE categories SET name = ? , description = ? WHERE id = ?");
        $stmt->execute(array($catname,$descr,$id));

                        //                           بص فوق


        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

            $url = 'categories.php?do=Manage';
            $seconds = 3;
            redirectSuccess($seconds,$url);

        }
} else {

    $errorMsg = "عدل ام الباراميترز";
    $url = 'categories.php';
redirectHome($errorMsg, 5 ,$url);
}

echo "</div>";
} elseif ($do == 'Delete') { // Delete Users

echo "<h1 class='text-center'>";
    echo lang('DELETE_MEMBER');
    echo "</h1>";
echo "<div class='container'>";

    // Check IF Get user request is numeric & get the int value of it

    $catid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($catid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete Category Depends on ID
    $stmt = $con->prepare("DELETE FROM categories WHERE id = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid); // Code from members
    // Excute Query  
    $stmt->execute(array($catid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Deleted </div> ';

    $url = 'categories.php?do=Manage';
    redirectSuccess(3,$url);

    } else {

    $errorMsg = 'This ID is not exist';
    $url = 'index.php';
    redirectHome($errorMsg,3,$url);
    }
    echo '</div>';
}

} else {

    header('Location: index.php');

    exit();

}


?>

<?php include $tpl . 'footer.php'; ?>

<?

ob_end_flush();

?>