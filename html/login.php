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
    <script type="text/javascript" src="/js/ShowPassword.js"></script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</head>

<body>

<?php include_once "header.php";?>

<?php include_once "include/errors.php";?>

<div class="box">
    <div class="box-row box-light">
        <b>Login</b>
    </div>
    <div class="box-row form">
        <form action="/login?action=login" method="post">
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
                    <input type="password" name="passwd" id="password1" size="18" maxlength="500">
                </label>
                <label class="form-after">
                    <input type="checkbox" onclick="ShowPassword()">
                    Display password characters entered
                </label>
            </fieldset>
            <fieldset>
                <legend>
                    Verify your humanity
                </legend>
                <div
                    class="h-captcha"
                    data-theme="dark"
                    data-sitekey="10000000-ffff-ffff-ffff-000000000001">
                </div>
            </fieldset>
            <input type="checkbox" name="stay_logged" value="1" tabindex="3">
            Remain logged in for the remainder of the session
            <br>
            <br>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>
<script>document.getElementById('uname').focus()</script>

</body>

</html>
