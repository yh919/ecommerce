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
        echo 'Manage';
    } elseif ($do == 'Add') { ?>



<h1 class="text-center">
    <?php echo lang('ADD_CATEGORY') ?>
</h1>
<div class="container">
    <form class="row g-3 form-group" action="?do=Insert" method="POST">
        <div class="col-md-4">
            <!-- <label for="username" class="form-label"><?php // echo lang('CATEGORY_NAME')?></label> -->
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required='required'
                placeholder="Enter Category Name">
        </div>
        <div class="col-md-4">
            <!-- <label for="fullname" class="form-label"><?php // echo lang('CATEGORY_DESCRIPTION')?></label> -->
            <input type="text" class="form-control" id="fullname" name="fullname" required='required'
                placeholder="Enter Description">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?php echo lang('SUBMIT') ?></button>
        </div>
    </form>
</div>
<?php } elseif ($do == 'Insert') { // Insert Member Page
echo 'Insert';
}
elseif ($do == 'Edit') { // Edit Page
echo 'Edit';

} elseif ($do == 'Update') {

echo 'Update';

} elseif ($do == 'Delete') { // Delete Users

echo 'Delete';

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