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
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        #product-image {
            flex-grow: 1;
        }

        #product-info {
            flex-grow: 8;
        }

        .box-item {
            padding: 16px;
        }

        h1 {
            margin-top: 8px;
        }

        img {
            margin-left: auto;
            margin-right: auto;
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
    <div class="box box-row box-container">
        <div id="product-image">
            <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583" alt="Example image"/>
        </div>
        <div id="product-info">
            <h1>
                Test product
                <span>€3,-</span>
            </h1>
            <a href="">Example user</a> on 01/01/2023
        </div>
    </div>

</body>

</html>
