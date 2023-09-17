<?php
ob_start(); // Output Buffering Start
// Manage Items Page
// You can Add || Edit || Delete Mebers

session_start();

$pageTitle = 'Items | Admin Panel';

if(isset($_SESSION['username'])) {

    // $pageTitle = 'Dashboard';

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') { // Manage Items Page

        $query = '';

        // Select Users from Database
        $stmt = $con->prepare("SELECT * FROM items");
        // Excute Statement
        $stmt->execute();
        // Assign to variable
        $rows = $stmt->fetchAll();



        ?>

<h1 class="text-center"><?php echo lang('MANAGE_ITEMS') ?></h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Item Name</td>
                <td>Category</td>
                <td>Price</td>
                <td>Control</td>
            </tr>


            <?php
            
            // $rowcatid = $rows['catid'];
            
            // $stmtcat = $con->prepare("SELECT id , name FROM categories WHERE id = $rowcatid");
            // $stmtcat->execute();
            // $catidget = $stmtcat->fetchAll();
            
            // echo $rows['catid'];
            ?>



            <?php

                // Select All Data Depend on this ID

                $stmt3 = $con->prepare("SELECT
                                            *
                                        FROM
                                        categories");
                $stmt3->execute();
                $cat = $stmt3->fetchAll();

                
                
                                // echo '<pre>';
                                // print_r($cat);
                                // echo '</pre>';
                                
                                
                                foreach($rows as $row) {
                                    // $accessstatus = '';
                                    // if ($row['groupid'] == 1) {
                                        // $accessstatus = lang('ADMIN');
                                        // } else {
                                            // $accessstatus = lang('MEMBER');
                                            $catname = '';
                                            foreach($cat as $ids) {
                                                if ($row['catid'] == $ids['id']) {
                                                    $catname = $ids['name'];
                                                }
                                            }




                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $catname . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                ?>
            <td>
                <a href="items.php?do=Edit&id=<?php echo $row['id'] ?>" class="btn btn-success">
                    Edit</a>
                <a href="items.php?do=Delete&id=<?php echo $row['id'] ?>" class="btn btn-danger confirm">
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
    <a class="btn btn-primary" href="items.php?do=Add"> <i class="fa-solid fa-plus fa-xl"></i> New Item </a>
</div>



<?php } elseif ($do == 'Add') { ?>



<h1 class="text-center">
    <?php echo lang('ADD_ITEM') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Insert" method="POST">
        <div class="col-md-4">
            <label for="itemname" class="form-label"><?php echo lang('ITEM_NAME')?></label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Item Name">
        </div>
        <div class="col-md-4">
            <label for="itemprice" class="form-label"><?php  echo lang('ITEM_PRICE')?></label>
            <input type="text" class="form-control" id="itemprice" name="itemprice" required='required'
                placeholder="Enter Item Price">
        </div>
        <div class="col-md-4">
            <label for="itemcat" class="form-label"><?php echo lang('ITEM_CATEGORY') ?></label>
            <select name="itemcatid" id="itemcatid" class="form-select">
                <option selected> Choose Category </option>
                <option value="1"> Mobiles </option>
                <option value="2"> TVs </option>
                <option value="3"> Books </option>
            </select>
        </div>
        <div class="col-md-12">
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

        $itemName   = $_POST['name'];
        $price   = $_POST['itemprice'];
        $catid = $_POST['itemcatid'];

        // Validate Form

        $formErrors = array();


        if (empty($itemName)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($price)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        // If no error >> Update Operation

        if (empty($formErrors)) {

        // Check if user exist in database

        $check = checkItem("item_name", "items", $itemName);


        if ($check == 1) {

            echo   "<div class='alert alert-warning'> <strong> Username $itemName already exist </strong> </div>";

            redirectSuccess(6,$url);

        }     else {

        // Insert info into database

        // INSERT INTO `items` (`id`, `item_name`, `catid`, `price`) VALUES (NULL, 'Samsung A21', '1', '1750');


        $stmt = $con->prepare("INSERT INTO
                                            items (item_name, catid, price)
                                            VALUES ( :zitemname , :zcatid , :zprice)");
        $stmt->execute(
            array(
                ':zitemname' => $itemName,
                ':zcatid' => $catid,
                ':zprice' => $price,
            )
        );
        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Added </div> ';

        $url = 'items.php?do=Manage';
        
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

        $itemid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        // Select All Data Depend on this ID
        $stmt = $con->prepare("SELECT * FROM items WHERE id = ? LIMIT 1");

        // Execute Query
        $stmt->execute(array($itemid));

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
        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
        <div class="col-md-4">
            <label for="itemname" class="form-label"><?php echo lang('ITEM_NAME')?></label>
            <input type="text" class="form-control" id="name" name="itemname" autocomplete="off" required='required'
                placeholder="Enter Item Name" value="<?php echo $row['item_name'] ?>">
        </div>
        <div class="col-md-4">
            <label for="itemprice" class="form-label"><?php  echo lang('ITEM_PRICE')?></label>
            <input type="text" class="form-control" id="itemprice" name="itemprice" required='required'
                placeholder="Enter Item Price" value="<?php echo $row['price'] ?>">
        </div>
        <div class="col-md-4">
            <label for="itemcat" class="form-label"><?php echo lang('ITEM_CATEGORY') ?></label>
            <select name="itemcatid" id="itemcatid" class="form-select">
                <option selected> Choose Category </option>
                <option value="1"> Mobiles </option>
                <option value="2"> TVs </option>
                <option value="3"> Books </option>

            </select>
        </div>
        <div class="col-md-12">
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

        $id             = $_POST['itemid'];
        $itemName       = $_POST['itemname'];
        $price          = $_POST['itemprice'];
        $catid          = $_POST['itemcatid'];

       // 5od alcode mn ?do=Add


        // Validate Form

        $formErrors = array();

        // 5od alcode mn $do=Add
if (empty($itemName)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($price)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        // If no error >> Update Operation

        if (empty($formErrors)) {

        // Check if user exist in database

        $check = checkItem("name", "categories", $itemName);


        if ($check == 1) {

            echo   "<div class='alert alert-warning'> <strong> Username $itemName already exist </strong> </div>";

            redirectSuccess(6,$url);

        }
        // Update Database with updated information


            //   متنساش تغير الباراميترز في الستاتمينت عشان متقعدش تدور علي الايرور فين ساعه
            //                                       متنساش

        $stmt = $con->prepare("UPDATE items SET item_name = ? , catid = ? , price = ? WHERE id = ?");
        $stmt->execute(array($itemName,$catid,$price,$id));

                        //                           بص فوق


        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

            $url = 'items.php?do=Manage';
            $seconds = 3;
            redirectSuccess($seconds,$url);

        }
} else {

    $errorMsg = "عدل ام الباراميترز";
    $url = 'items.php';
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
    $stmt = $con->prepare("SELECT * FROM items WHERE id = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($catid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete Category Depends on ID
    $stmt = $con->prepare("DELETE FROM items WHERE id = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid); // Code from members
    // Excute Query  
    $stmt->execute(array($catid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Deleted </div> ';

    $url = 'items.php?do=Manage';
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