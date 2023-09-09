<?php

ob_start(); // Output Buffering Start

session_start();

if(isset($_SESSION['username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';

    // Dashboard Page Content
    // Latest Users Function Parameters
    $latestusers = 5;
    $thelatest = getLatest("*", "users","userid",$latestusers);

    // EndOf Latest Users Function Parameters;

?>
<div class="container home-stat text-center">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="stat st-members">
                Total Members
                <span><a href="members.php?do=Manage">
                        <?php echo countItems('userid' , 'users') ?>
                    </a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-pending">
                Pending Members
                <span><a href="members.php?page=Pending">
                        <?php echo checkItem('regstatus','users','0') ?>
                    </a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-categories">
                Total Categories
                <span><a href="categories.php">
                        <?php echo countItems('id', 'categories') ?>
                    </a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-items">
                Total Items
                <span>1500</span>
            </div>
        </div>
    </div>
</div>
<div class="container latest">
    <div class="row">
        <div class="col-sm-6">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">
                    <ul class="list-unstyled">
                        Latest <?php echo $latestusers ?> Registerd Users
                </div>
                <div class="card-body latest-users">
                    <?php
                        foreach ($thelatest as $user) { ?>
                    <li>
                        <?php echo $user['username'] ?>
                        <?php
                    if ($user['regstatus'] == 0) {
                        ?>
                        <a href="members.php?do=Activate&userid=<?php echo $user['userid'] ?>"
                            class="btn btn-warning float-end btn-latest text-center">
                            Activate
                        </a>
                        <?php
                    }?>
                        <a href="members.php?do=Edit&userid=<?php echo $user['userid'] ?>">
                            <span class="btn btn-success float-end btn-latest text-center">Edit</span></a>
                    </li>

                    <?php }
                ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card text-black bg-light mb-3">
                <div class="card-header">
                    Latest Items Added
                </div>
                <div class="card-body">
                    Test
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    // Dashboard Page Content end

} else {
    header('Location: index.php');
    exit();
}

include $tpl . 'footer.php';

ob_end_flush();

?>