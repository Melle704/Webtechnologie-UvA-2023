<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Registration</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>
    <div class="box">
        <div class="box-head">
            <div class="box-row">
                <div class="box-title">
                    <span>MAGIC</span> THE GATHERING
                </div>
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

    <div class="box">
        <div class="box-row box-light">
            <b>Register</b>
        </div>
        <div class="box-row">
            <form action="/register.php?register/register" method="post" onsubmit="this.register.disabled=true;if(process_form(this)){return true;}else{this.register.disabled=false;return false;}">
            </form>
            form
        </div>
    </div>

    <div class="box box-row">
        <a href="https://cloudfront-us-east-1.images.arcpublishing.com/metroworldnews/IFAKFZOGE5GXRBOPSAXN334SOY.jpg" target="_blank">credits to the chinese government</a>
    </div>

</body>

</html>
