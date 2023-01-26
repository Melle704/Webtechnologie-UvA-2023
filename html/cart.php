<?php
include_once "include/common.php";
include_once "include/errors.php";
include_once "include/db.php";

session_start();

$cart_empty = (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) === 0);

// Ensure user is logged in and has items in their cart
if (!isset($_SESSION["id"]) || $cart_empty) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["id"])) {
        // Ignore post if not logged in
        exit();
    }

    if ($_POST["action"] == "remove" && isset($_POST["id"])) {
        unset($_SESSION["cart"][$_POST["id"]]);
        header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
    }

    if ($_POST["action"] == "checkout") {
        if (!$cart_empty) {
            header("Location: checkout.php");
        } else {
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
        }
    }
}

if (!$cart_empty) {
    $keys_string = implode(',', array_keys($_SESSION["cart"]));

    $sql = "SELECT * FROM cards WHERE id IN ($keys_string)";
    $products = query_execute($db, $sql);

    $total = 0;
    foreach ($products as $product) {
        $amount = $_SESSION["cart"][$product["id"]];
        $total += $product["normal_price"] * $amount;
    }
} else {
    $products = [];
    $total = 0;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Cart</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/shop.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>

<?php include_once "header.php"; ?>

<?php include_once "include/errors.php"; ?>

<div class="box box-row box-container">
    <div id="cart-list">
        <h1>Cart</h1>
        <table class="box">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Total Price</th>
                <th width="30px"></th>
            </tr>
            <?php foreach($products as $product): ?>
            <tr>
                <td class="col-text">
                    <a href="product.php?id=<?= $product["id"] ?>">
                        <?= $product["name"] ?>
                    </a>
                </td>
                <td class="col-num"><?= format_eur($product["normal_price"]) ?></td>
                <td class="col-num"><?= $_SESSION["cart"][$product["id"]] ?></td>
                <td class="col-num">
                    <?= format_eur($_SESSION["cart"][$product["id"]] * $product["normal_price"]) ?>
                </td>
                <td>
                    <form method="post" action="" class="form remove-form">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="id" value="<?= $product["id"] ?>">
                        <input type="submit" value="&#x2716;">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($cart_empty): ?>
            <tr>
                <td colspan="5" style="text-align: center; font-size: 1rem; padding: 1rem;">
                    Your cart is empty, consider going to the <a href="shop.php">shop</a> to add items.
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <div id="cart-details" class="box box-row ">
        <h2>
        Total: <?= format_eur($total) ?>
        </h2>

        <?php if (!$cart_empty): ?>
        <form method="post" class="form">
            <input type="hidden" name="action" value="checkout">
            <input type="submit" value="Checkout">
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
