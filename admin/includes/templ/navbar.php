<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN')?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav"
            aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="categories.php"><?php echo lang('CATEGORIES')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="items.php"><?php echo lang('ITEMS')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page"
                        href="members.php?do=Manage"><?php echo lang('MEMBERS')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="shops.php"><?php echo lang('SHOPS')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#"><?php echo lang('LOGS')?></a>
                </li>
                <li class="nav-item dropdown mr-auto">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php echo $_SESSION['username']?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="members.php?do=Edit&userid=<?php echo $_SESSION['id'] ?>"><?php echo lang('EDIT_PROFILE')?></a>
                        </li>
                        <li><a class="dropdown-item" href="#"><?php echo lang('SETTINGS')?></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT')?></a></li>
                    </ul>
                </li>
            </ul>
            </li>
            </ul>
        </div>
    </div>
</nav>