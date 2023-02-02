<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["id"])) {
    require_once "include/db.php";
    include_once "include/common.php";

    logout_user_on_inactivity($db);
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
        <div class="box-row box-head-row">
            <div class="box-title">
                <a href="/index"><span>URZA'S</span> WEBSHOP</a>
            </div>
        </div>
        <div class="box-row box-head-items box-light">
            <div class="box-left">
                <ul>
                    <li><a href="/index">Index</a></li>
                    <li><a href="/shop">Shop</a></li>
<?php if (isset($_SESSION["id"])): ?>
                    <li><a href="/forum">Forum</a></li>
<?php endif; ?>
                </ul>
            </div>
            <div class="flex-break"></div>
            <div class="box-right">
                <ul>
<?php if (isset($_SESSION["cart"])): ?>
                    <li><a href="/cart">Cart (<?= $cart_size ?>)</a></li>
<?php endif; ?>
<?php if (isset($_SESSION["id"])): ?>
                    <li><a href="/purchases">Purchases</a></li>
                    <li><a href="/profile?id=<?= $_SESSION["id"] ?>">Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
<?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
<?php endif; ?>
                </ul>
            </div>
        </div>
<?php if (isset($_SESSION["uname"])): ?>
        <div class="box-row box-head-row">
            <div class="box-left">
                Logged in as <b><?= $_SESSION["uname"] ?></b>
            </div>
            <div class="box-right" id="datetime"></div>
        </div>
<?php endif; ?>
    </div>
</div>
