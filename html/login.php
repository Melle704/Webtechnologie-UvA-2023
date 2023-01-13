<?php include_once "include/user_auth.php";?>
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

<?php include_once "header.php";?>

<?php include_once "include/errors.php";?>

<div class="box">
    <div class="box-row box-light">
        <b>Login</b>
    </div>
    <div class="box-row form">
        <form action="/login.php?action=login" method="post">
            <fieldset>
                <legend>
                    Insert your username
                </legend>
                <b>Username</b>
                <label>
                    <input id="uname" type="text" name="uname" size="25" maxlength="25">
                </label>
            </fieldset>
            <fieldset>
                <legend>
                    Enter your password
                </legend>
                <label>
                    <b>Password</b>
                    <input type="password" name="passwd" size="18" maxlength="500">
                </label>
                <label class="form-after">
                    <input type="checkbox" name="stay_logged" value="1" tabindex="3">
                    Remain logged in till the session expires
                </label>
            </fieldset>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</div>

<div class="box box-row">
    <a href="https://cloudfront-us-east-1.images.arcpublishing.com/metroworldnews/IFAKFZOGE5GXRBOPSAXN334SOY.jpg" target="_blank">credits to the CCP</a>
</div>

<script>document.getElementById('uname').focus()</script>
</body>

</html>
