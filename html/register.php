<?php include_once "include/user_auth.php";?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Registration</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script type="text/javascript" src="/js/MatchPassword.js"></script>
    <script type="text/javascript" src="/js/ShowPassword.js"></script>
</head>

<body>
<?php include_once "header.php";?>
<?php include_once "include/errors.php";?>

<div class="box">
    <div class="box-row box-light">
        <b>Register</b>
    </div>

    <div class="box-row form">
        <form action="/register.php?action=register" method="post">
            <fieldset>
                <legend>
                    Enter your preferred username.
                </legend>
                <b>Username</b>
                <label>
                    <input id="uname" type="text" name="uname" size="25" maxlength="25">
                </label>
            </fieldset>
            <fieldset>
                <legend>
                    Your password must be at least 8 chatacters in length and contain 1 special symbol.
                </legend>
                <label>
                    <b>Password</b>
                    <input type="password" name="passwd1" id="password1" size="18" maxlength="500" onkeyup='check();'>
                </label>
                <label>
                    <b>Confirm Password</b>
                    <input type="password" name="passwd2" id="password2" size="18" maxlength="500" onkeyup='check();'>
                </label>
                <label>
                    <b class="password-match" id="message"></b>
                </label>
                <label class="form-after">
                    <input type="checkbox" onclick="ShowPassword()">
                    Display password characters entered
                </label>
            </fieldset>
            <fieldset>
                <legend>
                    Please specify your valid email address.
                </legend>
                <b>Email</b>
                <label>
                    <input type="email" name="email" size="30" maxlength="30">
                </label>
            </fieldset>
            <fieldset>
                <legend>
                    Please enter your date of birth.
                </legend>
                <b>Date of Birth</b>
                <label>
                    <select name="day">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>
                </label>
                <label>
                    <select name="month">
                        <option value="1">January</option>
                        <option value="2">Febuary</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </label>
                <label>
                    <input name="year" placeholder="year" size="4" maxlength="4">
                </label>
            </fieldset>
            By registering, you agree to accept our <a href="/cookies.php">cookie policy</a>.
            <br>
            <br>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>
<script>document.getElementById('uname').focus()</script>
</body>

</html>
