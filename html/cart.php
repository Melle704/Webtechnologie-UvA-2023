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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 

    <style>
        * {
            box-sizing: border-box;
        }

        h1 {
            margin-top: 8px;
        }

        table {
            width: 100%;
        }

        th {
            background-color: #3d3c3b;
        }

        form {
            margin: 0 !important;
            display: inline;
        }
    </style>
</head>

<body>
    <?php include_once "header.php"; ?>

    <?php
        session_start();
        if ($_POST["action"] == "remove" && isset($_POST["id"])) {
            unset($_SESSION["cart"][$_POST["id"]]);
            header("Location: " . $_SERVER["PHP_SELF"], true, 303);
        }
    ?>

    <div class="box box-row">
    <h1>Cart</h1>
    <table class="box">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Amount</th>
            <th width="30px"></th>
        </tr>
        <?php foreach($_SESSION["cart"] as $id => $amount): ?>
        <tr>
            <td>Example (<?= $id ?>)</td>
            <td>€3,-</td>
            <td><?= $amount ?></td>
            <td>
                <form method="post" action="" class="form">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="submit" value="&#x2716;">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <form class="form">
        <input type="submit" value="Purchase">
    </form>
    </div>

    <?php include_once "footer.php"; ?>
</body>

</html>
