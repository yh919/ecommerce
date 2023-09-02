<?php

session_start();


$noNavbar = '';
$pageTitle = 'Admin Login';

if(isset($_SESSION['username'])) {
    header("Location: dashboard.php");
}


include 'init.php';


    //  Check if user coming from http request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // Check user from database

        $stmt = $con->prepare("SELECT
        userid, username , password
        FROM
            users
        WHERE
            username = ?
        AND
            password = ?
        AND
            groupid = 1
        LIMIT 1");
        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();


        // If Count > 0 = EXIST USER

        if ($count > 0) {
            $_SESSION['username'] = $username; // Register Session Name
            $_SESSION['id'] = $row['userid']; // Register Session Id
            header('Location: dashboard.php'); // Redirect to dashboard
            exit();
        }

    }

?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="login">
    <h2 class="text-center login-header">Admin Login</h2>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary float-end" type="submit" value="Login">
</form>




<?php include $tpl . 'footer.php'; ?>