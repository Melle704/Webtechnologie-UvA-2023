<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["id"])) {
    require_once "include/db.php";
    include_once "include/common.php";

    $now = time();
    $now = new DateTime("@$now");

    $dt = $now->diff($_SESSION["last_activity"]);
    $mins_logged_in = $dt->days * 24 * 60;
    $mins_logged_in += $dt->h * 60;
    $mins_logged_in += $dt->i;

    // logout user after 10 minutes of inactivity
    if ($mins_logged_in >= 10 && !$_SESSION["stay_logged"]) {
        session_start();
        session_destroy();
        header("Location: /index.php");
        exit;
    }

    update_user_activity($db, $_SESSION["id"]);
}

$cart_size = 0;
if (isset($_SESSION["cart"])) {
    foreach($_SESSION["cart"] as $id => $amount) {
        $cart_size += $amount;
    }
}
?>

<div class="box">
    <div class="box-head">
        <div class="box-row">
            <div class="box-title">
                <span>MAGIC</span> THE GATHERING
            </div>
        </div>
        <div class="box-row box-light">
            <div class="box-left">
                <ul>
                    <li><a href="index.php">Index</a></li>
                    <li><a href="cards.php">Cards</a></li>
                    <li><a href="shop.php">Shop</a></li>
                </ul>
            </div>
            <div class="box-right">
                <ul>
                <?php if (isset($_SESSION["cart"])): ?>
                    <li><a href="cart.php">Cart (<?= $cart_size ?>)</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION["id"])): ?>
                    <li><a href="profile.php?id=<?php echo $_SESSION["id"];?>">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php if (isset($_SESSION["uname"])): ?>
        <div class="box-row">
            <div class="box-left">
                Logged in as <b><?php echo $_SESSION["uname"];?></b>
            </div>
            <div class="box-right" id="datetime"></div>
        </div>
        <?php endif; ?>
    </div>
</div>
