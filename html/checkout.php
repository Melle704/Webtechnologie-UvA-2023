<?php

include_once "include/common.php";
include_once "include/db.php";
include_once "include/payment.php";

session_start();

$cart_empty = (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) === 0);

// Ensure user is logged in and has items in their cart
if (!isset($_SESSION["id"]) || $cart_empty) {
    header("Location: /");
    exit;
}

$keys_string = implode(',', array_keys($_SESSION["cart"]));

$sql = "SELECT * FROM cards WHERE id IN ($keys_string)";
$products = query_execute($db, $sql);

$total = 0;
foreach ($products as $product) {
    $amount = $_SESSION["cart"][$product["id"]];
    $total += $product["normal_price"] * $amount;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id"])) {
    if ($cart_empty || $total == 0) {
        reload_err("Cart should not be empty");
    }

    $name = htmlspecialchars(trim($_POST["name"]));
    $address = htmlspecialchars(trim($_POST["address"]));
    $postcode = htmlspecialchars(trim($_POST["postcode"]));
    $city = htmlspecialchars(trim($_POST["city"]));

    validate_not_empty(
        ["name", $name],
        ["address", $address],
        ["postcode", $postcode],
        ["city", $city],
    );

    validate_predicates(
        ["Name is too long (max 80)", strlen($name) <= 80],
        ["Address is too long (max 80)", strlen($name) <= 80],
        ["Postcode is invalid", preg_match("/^\d{4} [A-Z]{2}$/", $postcode) === 1],
        ["City name is too long (max 30)", strlen($name) <= 30],
    );

    $mollie_id = make_payment($total);

    if ($mollie_id == false) {
        header("Location: /purchase?result=failure");
    }

    $sql = "INSERT INTO purchases (uid, mollie_id, status, name, address, postcode, city, price, time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, now())";

    query_execute($db, $sql, "issssssd", $_SESSION["id"],
                  $mollie_id, "open", $name, $address, $postcode, $city, $total);

    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Checkout</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/shop.css">
</head>

<body>

<?php include_once "header.php"; ?>
<?php include_once "include/errors.php"; ?>

<div class="box">
    <div class="box-row box-light">
        <h1>Checkout</h1>
    </div>
    <div class="box-row">
        <div class="box-row" style="font-size: 1rem; background-color: #202020;">
        Order total: <b><?= format_eur($total) ?></b>
        </div>
        <form method="post" class="form checkout">
            <fieldset class="address">
                <legend>Shipping address (currently Netherlands only)</legend>

                <label>
                    <b>Name</b>
                    <input name="name" required>
                </label>

                <label>
                    <b>Address (Street and house number)</b>
                    <input name="address" placeholder="Science Park 900" required>
                </label>

                <label>
                    <b>Postal code</b>
                    <input name="postcode" placeholder="1098 XH" required>
                </label>

                <label>
                    <b>City</b>
                    <input name="city" placeholder="Amsterdam" required>
                </label>
            </fieldset>

            <b>Important note</b>
            <p>
                Clicking pay now will lead to a page where a mock payment can be made.<br>
                We do not currently sell any products, and you will also not be charged any money.
            </p>

            <br>
            <input type="submit" value="Pay now">
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
