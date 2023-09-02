<?php
ob_start(); // Output Buffering Start
// Manage Members Page
// You can Add || Edit || Delete Mebers

session_start();

$pageTitle = 'Members | Admin Panel';

if(isset($_SESSION['username'])) {

    // $pageTitle = 'Dashboard';

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') { // Manage Members Page

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $idreg = 0;
            $query = "WHERE regstatus = $idreg";
        }
        // Select Users from Database
        $stmt = $con->prepare("SELECT * FROM users $query");
        // Excute Statement
        $stmt->execute();
        // Assign to variable
        $rows = $stmt->fetchAll();

    ?>

<h1 class="text-center"><?php echo lang('MANAGE_MEMBERS') ?></h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Access</td>
                <td>Username</td>
                <td>Password</td>
                <td>Fullname</td>
                <td>Email</td>
                <td>Registerd Date</td>
                <td>Control</td>
            </tr>

            <?php

            foreach($rows as $row) {
                $accessstatus = '';
                if ($row['groupid'] == 1) {
                    $accessstatus = lang('ADMIN');
                } else {
                    $accessstatus = lang('MEMBER');
                }

                echo "<tr>";
                echo "<td>" . $row['userid'] . "</td>";
                echo "<td>" . $accessstatus . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "<td>" . $row['fullname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['regdate'] . "</td>";
                ?>
            <td>
                <a href="members.php?do=Edit&userid=<?php echo $row['userid'] ?>" class="btn btn-success">
                    Edit</a>
                <a href="members.php?do=Delete&userid=<?php echo $row['userid'] ?>" class="btn btn-danger confirm">
                    Delete</a>
                <?php
                    if ($row['regstatus'] == 0) {
                        ?>
                <a href="members.php?do=Activate&userid=<?php echo $row['userid'] ?>" class="btn btn-warning confirm">
                    Activate
                </a>
                <?php
                    } elseif ($row['regstatus'] == 1) {
                        ?>
                <a href="members.php?do=Deactivate&userid=<?php echo $row['userid'] ?>" class="btn btn-warning confirm">
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
    <a class="btn btn-primary" href="members.php?do=Add"> <i class="fa-solid fa-plus fa-xl"></i> New Member </a>
</div>


<?php } elseif ($do == 'Add') { // Add Members Page ?>

<h1 class="text-center">
    <?php echo lang('ADD_MEMBER') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Insert" method="POST">
        <div class="col-md-4">
            <label for="username" class="form-label"><?php echo lang('USERNAME')?></label>
            <input type="text" class="form-control" id="username" name="username" autocomplete="off" required='required'
                placeholder="Enter username">
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php echo lang('FULLNAME')?></label>
            <input type="text" class="form-control" id="fullname" name="fullname" required='required'
                placeholder="Enter full name">
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label"><?php echo lang('EMAIL_ADDRESS')?></label>
            <input type="email" class="form-control" id="email" name="email" required='required'
                placeholder="Enter email address">
        </div>
        <div class="col-md-4">
            <label for="password" class="form-label"><?php echo lang('PASSWORD')?></label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"
                placeholder="Enter password">
        </div>
        <div class="col-md-4">
            <label for="groupid" class="form-label"><?php echo lang('USER_ACCESS')?></label>
            <select class="form-select" name="groupid" aria-label="user group id select">
                <option value="1"><?php echo lang('ADMIN')?></option>
                <option value="0"><?php echo lang('MEMBER')?></option>
            </select>
        </div>
        <div class="col-md-4 select">
            <label for="trusteduser" class="form-label"><?php echo lang('TRUSTED_STATUS') ?></label>
            <select class="form-select" name="truststatus" aria-label="user trusted status select">
                <option value="1"><?php echo lang('TRUSTED_SELLER')?></option>
                <option value="0"><?php echo lang('NOT_TRUSTED_SELLER')?></option>
            </select>
        </div>
        <div class="col-md-4 select">
            <label for="regstatus" class="form-label"><?php echo lang('REG_STATUS') ?></label>
            <select class="form-select" name="regstatus" aria-label="user reg status select">
                <option value="1"><?php echo lang('REG_ACTIVATED')?></option>
                <option value="0"><?php echo lang('REG_NOT_ACTIVATED')?></option>
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

        $user   = $_POST['username'];
        $name   = $_POST['fullname'];
        $email  = $_POST['email'];
        $pass   = $_POST['password'];
        $hashPass = sha1($pass);
        $groupid = $_POST['groupid'];
        $trustedseller = $_POST['truststatus'];
        $regstatus = $_POST['regstatus'];

        // Validate Form

        $formErrors = array();

        if (strlen($user) < 4) {
            $formErrors[] = 'Username can\'t be less than <strong>4 Chars</strong>';
        }

        if (empty($user)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($name)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }
        if (empty($email)) {

            $formErrors[] = 'Email can\'t be <strong>Empty</strong>';
        }
        if (empty($pass)) {

            $formErrors[] = 'Pasword can\'t be <strong>Empty</strong>';
        }
        if ($pass < 6) {

            $formErrors[] = 'Pasword can\'t be less than <strong>6 Chars</strong>';
        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        // If no error >> Update Operation

        if (empty($formErrors)) {

        // Check if user exist in database

        $check = checkItem("username", "users", $user);

        // Check if email exist in database

        $checkMail = checkItem("username", "users", $email);


        if ($check == 1 || $checkMail == 1 ) {

            echo   "<div class='alert alert-warning'> <strong> Username $user already exist </strong> </div>";
            echo   "<div class='alert alert-warning'> <strong> Username $email already exist </strong> </div>";

            redirectSuccess(6,$url);

        }     else {

        // Insert info into database

        $stmt = $con->prepare("INSERT INTO
                                            users (username, password, email, fullname, groupid, truststatus , regstatus)
                                            VALUES (:zuser, :zpass, :zmail, :zname, :zgroupid, :ztruststatus, :zregstatus)");
        $stmt->execute(array(
            'zuser'        => $user,
            'zpass'        => $hashPass,
            'zmail'        => $email,
            'zname'        => $name,
            'zgroupid'     => $groupid,
            'ztruststatus' => $trustedseller,
            'zregstatus'   => $regstatus
        ));
        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

        $url = 'members.php?do=Manage';
        
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

        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

        // Select All Data Depend on this ID
        $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");

        // Execute Query
        $stmt->execute(array($userid));

        // Fetch the data
        $row = $stmt->fetch();

        // The Row Count
        $count = $stmt->rowCount();


        // Group id variables
        $usergroupid = '';
        if ($row['groupid'] == '1') {
            $usergroupid = lang('ADMIN');
        } else {
            $usergroupid = lang('MEMBER');
        }

        // Truster Seller variables
        $usertrusted = '';
        if ($row['truststatus'] == '1') {
            $usertrusted = lang('TRUSTED_SELLER');
        } else {
            $usertrusted = lang('NOT_TRUSTED_SELLER');
        }
        // If ID is valid >> Show Form

            if ($stmt->rowCount() > 0 ) { ?>

<h1 class="text-center">
    <?php echo lang('EDIT_MEMBER') ?>
</h1>
<div class="container">
    <form class="row g-3" action="?do=Update" method="POST">
        <input type="hidden" name="userid" value="<?php echo $userid ?>">
        <div class="col-md-4">
            <label for="username" class="form-label"><?php echo lang('USERNAME')?></label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'] ?>"
                autocomplete="off" required='required'>
        </div>
        <div class="col-md-4">
            <label for="fullname" class="form-label"><?php echo lang('FULLNAME')?></label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $row['fullname'] ?>"
                required='required'>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label"><?php echo lang('EMAIL_ADDRESS')?></label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email'] ?>"
                required='required'>
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label"><?php echo lang('PASSWORD')?></label>
            <input type="hidden" id="password" name="oldpassword" value="<?php echo $row['password']?>">
            <input type="password" class="form-control" id="password" name="newpassword" autocomplete="new-password"
                placeholder="Leave it blank if you don't need to change">
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label"><?php echo lang('VERIFY_PASSWORD')?></label>
            <input type="password" class="form-control" id="password" name="verifypassword" autocomplete="new-password"
                placeholder="Retype your password or Leave it blank if you don't need to change">
        </div>
        <div class="col-md-4 select">
            <label for="useracces" class="form-label">User Access</label>

            <?php if ($row['groupid'] == 1) { ?>
            <select class="form-select" name="membergroupid" aria-label="user group id select">
                <option selected value="<?php echo $row['groupid'] ?>"> <?php echo $usergroupid ?></option>
                <option value="0"><?php echo lang('MEMBER')?></option>
            </select>
            <?php } elseif ($row['groupid'] == 0) { ?>
            <select class="form-select" name="membergroupid" aria-label="user group id select">
                <option selected value="<?php echo $row['groupid'] ?>"> <?php echo $usergroupid ?></option>
                <option value="1"><?php echo lang('ADMIN')?></option>
            </select>
            <?php } ?>
        </div>
        <div class="col-md-4 select">
            <label for="trusteduser" class="form-label">Trusted Status</label>
            <?php if ($row['truststatus'] == 1) { ?>
            <select class="form-select" name="truststatus" aria-label="user group id select">
                <option selected value="<?php echo $row['truststatus'] ?>"><?php echo $usertrusted ?></option>
                <option value="0"><?php echo lang('NOT_TRUSTED_SELLER')?></option>
            </select>
            <?php } elseif ($row['truststatus'] == 0) { ?>
            <select class="form-select" name="truststatus" aria-label="user group id select">
                <option selected value="<?php echo $row['truststatus'] ?>"><?php echo $usertrusted ?></option>
                <option value="0"><?php echo lang('TRUSTED_SELLER')?></option>
            </select>
            <?php } ?>
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

        $id     = $_POST['userid'];
        $user   = $_POST['username'];
        $name   = $_POST['fullname'];
        $email  = $_POST['email'];
        $oldpass = $_POST['oldpassword'];
        $newpass = $_POST['newpassword'];
        $verifypass = $_POST['verifypassword'];
        $groupid = $_POST['membergroupid'];
        $truststatus = $_POST['truststatus'];


        // Password Trick


        $pass = '';

        if (empty($newpass)) {

            $pass = $oldpass;

        } else {

            $pass = sha1($newpass);
        }

        // Validate Form

        $formErrors = array();

        if (strlen($user) < 4) {
            $formErrors[] = 'Username can\'t be less than <strong>4 Chars</strong>';
        }

        if (empty($user)) {

            $formErrors[] = 'Username can\'t be <strong>Empty</strong>';
        }

        if (empty($name)) {

            $formErrors[] = 'Full Name can\'t be <strong>Empty</strong>';;

        }
        if (empty($email)) {

            $formErrors[] = 'Email can\'t be <strong>Empty</strong>';
        }
        
        if ($verifypass != $newpass) {

            $formErrors[] = '<strong>Your password didn\'t match verify password</strong>';
        }

        // Loop into error array > Show it
        foreach($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
            $url = $_SERVER['HTTP_REFERER'];
            redirectSuccess(3,$url);
        }

        // If no error >> Update Operation

        if (empty($formErrors)) {


        // Update Database with updated information

        $stmt = $con->prepare("UPDATE users SET username = ? , password = ? , email = ? , fullname = ? , groupid = ? , truststatus = ? WHERE userid = ?");
        $stmt->execute(array($user,$pass,$email,$name,$groupid,$truststatus,$id));

        // Echo Success Message

        echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Updated </div> ';

            $url = 'members.php?do=Manage';
            $seconds = 3;
            redirectSuccess($seconds,$url);

        }
} else {

    $errorMsg = "You Can't Access this page";
    $url = 'members.php?do=Edit&userid=' . $_SESSION['id'];
redirectHome($errorMsg, 5 ,$url);
}

echo "</div>";
} elseif ($do == 'Delete') { // Delete Users

echo "<h1 class='text-center'>";
    echo lang('DELETE_MEMBER');
    echo "</h1>";
echo "<div class='container'>";

    // Check IF Get user request is numeric & get the int value of it

    $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($userid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete User Depends on USERID
    $stmt = $con->prepare("DELETE FROM users WHERE userid = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($userid));

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

    $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($userid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete User Depends on USERID
    $stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userid = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($userid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Activated </div> ';

    $url = 'members.php?do=Manage';
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

    $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

    // Select All Data Depend on this ID
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");

    // Execute Query
    $stmt->execute(array($userid));

    // Fetch the data
    $row = $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();

    if ($stmt->rowCount() > 0 ) {

    // Delete User Depends on USERID
    $stmt = $con->prepare("UPDATE users SET regstatus = 0 WHERE userid = ? ");
    // Bind Parameter to $userid
    // $stmt->bindParam(":zuser", $userid);
    // Excute Query
    $stmt->execute(array($userid));

    echo "<div class='alert alert-success'></strong>" . $stmt->rowCount() . ' </strong>Record Dectivated </div> ';

    $url = 'members.php?do=Manage';
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