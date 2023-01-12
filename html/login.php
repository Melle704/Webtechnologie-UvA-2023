<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Login</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
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
            <b>Login</b>
        </div>
        <div class="box-row form">
            <form action="/login.php?login/login" method="post" onsubmit="validate_form()">
                <fieldset>
                    <legend>
                        Insert your username
                    </legend>
                    <b>Username</b>
                    <label>
                        <input id="uname" type="text" name="uname" value="" size="25" maxlength="25">
                    </label>
                </fieldset>
                <fieldset>
                    <legend>
                        Enter your password
                    </legend>
                    <label>
                        <b>Password</b>
                        <input type="password" name="passwd" value="" size="18" maxlength="500">
                    </label>
                    <label class="form-after">
                        <input type="checkbox" name="stay_logged" value="1" tabindex="3">
                        Remain logged in till the session expires
                    </label>
                </fieldset>
            </form>
            <input type="submit" name="login" value="Login">
        </div>
    </div>

    <div class="box box-row">
        <a href="https://cloudfront-us-east-1.images.arcpublishing.com/metroworldnews/IFAKFZOGE5GXRBOPSAXN334SOY.jpg" target="_blank">credits to the CCP</a>
    </div>

    <script>document.getElementById('uname').focus()</script>
</body>

</html>
