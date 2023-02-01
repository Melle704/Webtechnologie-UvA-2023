<?php
include_once "include/common.php";
include_once "include/errors.php";
include_once "include/db.php";

session_start();

// Ensure user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM purchases WHERE uid=? ORDER BY time DESC";

$purchases = query_execute($db, $sql, "i", $_SESSION["id"])

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Purchases</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/shop.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>

<?php include_once "header.php"; ?>

<?php include_once "include/errors.php"; ?>

<div class="box">
    <div class="box-row box-light">
        <h1>Purchases</h1>
    </div>
    <div class="box-row box-container">
        <table class="box">
            <tr>
                <th>Purchase id</th>
                <th>Price</th>
                <th>Timestamp</th>
            </tr>
            <?php foreach($purchases as $purchase): ?>
            <tr>
                <td class="col-text"><?= $purchase["id"] ?></td>
                <td class="col-num"><?= format_eur($purchase["price"]) ?></td>
                <td><?= $purchase["time"] ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($purchases) === 0): ?>
            <tr>
                <td colspan="5" style="text-align: center; font-size: 1rem; padding: 1rem;">
                    You have not made any purchases yet.
                </td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
