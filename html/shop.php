<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Shop</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 

    <style>
        * {
            box-sizing: border-box;
        }

        img {
            display: block;
            max-width: 100%;
            width: 265px;
            height: 370px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .box-item {
            padding: 16px;
        }

        .box-item h2 {
            margin-top: 0;
        }
    </style>
</head>

<body>

<?php include_once "header.php"; ?>

<div class="box box-row box-container">
    <?php
    include_once "include/db.php";

    $sql = "SELECT * FROM products";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    while($product = mysqli_fetch_assoc($query)):
    ?>
        <div class="box box-item">
            <h2>
                <a href="product.php?id=<?= $product["id"] ?>"><?= $product["name"] ?></a>
                <span class="box-right">
                    €<?= $product["price"] ?>
                </span>
                </h2>
                <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583" alt="<?= $product["name"] ?>"/>
        </div>
    <?php
    endwhile;
    mysqli_stmt_close($stmt);
    ?>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
