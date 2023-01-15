<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Cart</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
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
    </style>
</head>

<body>
    <div class="box">
        <div class="box-head">
            <div class="box-row box-title">
                <span>MAGIC</span> THE GATHERING
            </div>
            <div class="box-row box-light">
                <div class="box-left">
                    <ul>
                        <li><a href="index.php">Index</a></li>
                        <li><a href="cards.php">Cards</a></li>
                    </ul>
                </div>
                <div class="box-right">
                    <ul>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-row">
    <h1>Cart</h1>
    <table class="box">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Amount</th>
        </tr>
        <?php
            session_start();
            foreach($_SESSION["cart"] as $id => $amount): 
        ?>
        <tr>
            <td>Example (<?= $id ?>)</td>
            <td>€3,-</td>
            <td><?= $amount ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <form>
        <input type="submit" value="Purchase">
    </form>
    </div>

    <?php include_once "footer.php"; ?>
</body>

</html>
