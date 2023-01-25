<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$cart_empty = (!isset($_SESSION["cart"]) && count($_SESSION["cart"]) === 0);

// Ensure user is logged in and has items in their cart
if (!isset($_SESSION["id"]) || $cart_empty) {
    header("Location: index.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $postcode = trim($_POST["postcode"]);
    $city = trim($_POST["city"]);

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

    $sql = "INSERT INTO purchases (uid, name, address, postcode, city, price, time)
            VALUES (?, ?, ?, ?, ?, ?, now())";

    query_execute($db, $sql, "issssd", $_SESSION["id"],
                  $name, $address, $postcode, $city, $total);

    header("Location: purchase.php?result=success");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

<?php include_once "header.php"; ?>
<?php include_once "include/errors.php"; ?>

<div class="box box-row ">
    <h1>Checkout</h1>
    <div class="box-row box-light" style="font-size: 1rem;">
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

        <fieldset>
            <legend>Payment (currently iDeal only)</legend>

            <label>
                <b>Bank</b>
                <select>
                    <option>Example bank</option>
                </select>
            </label>
        </fieldset>
        <input type="submit" value="Pay now">
    </form>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
