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
            gap: 0.5rem;
        }

        .box-item {
            padding: 1rem;
        }

        .box-item h2 {
            margin-top: 0;
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
        <?php
        $cards = ["Test", "Test", "Test", "Test", "Test", "Test", "Test"];
        foreach($cards as $card):
        ?>
            <div class="box box-item">
                <h2>
                <?php echo $card; ?>
                </h2>
                <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583"/>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>
