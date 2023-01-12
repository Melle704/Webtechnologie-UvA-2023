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
        <div class="box-row form">
            <form action="/register.php?register/register" method="post" onsubmit="validate_form()">
                <fieldset>
                    <legend>
                        A username between 1 and 25 characters (with no embedded sql code)
                    </legend>
                    <b>Username</b>
                    <label>
                        <input id="uname" type="text" name="uname" value="" size="25" maxlength="25">
                    </label>
                </fieldset>
                <fieldset>
                    <legend>
                        Enter a password of at least length 8 that includes at least one symbol
                    </legend>
                    <label>
                        <b>Password</b>
                        <input type="password" name="passwd1" value="" size="18" maxlength="500">
                    </label>
                    <label>
                        <b>Confirm Password</b>
                        <input type="password" name="passwd2" value="" size="18" maxlength="500">
                    </label>
                </fieldset>
                <fieldset>
                    <legend>
                        Please specify an email address you have access to
                    </legend>
                    <b>Email</b>
                    <label>
                        <input type="email" name="uname" value="" size="25" maxlength="25">
                    </label>
                </fieldset>
                <fieldset>
                    <legend>
                        Insert your DOB (only admins will have access to this by default)
                    </legend>
                    <b>Date of Birth</b>
                    <label>
                        <select name="Day">
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
            </form>
            <input type="submit" name="register" value="Register">
        </div>
    </div>

    <div class="box box-row">
        <a href="https://cloudfront-us-east-1.images.arcpublishing.com/metroworldnews/IFAKFZOGE5GXRBOPSAXN334SOY.jpg" target="_blank">credits to the CCP</a>
    </div>

</body>

<script>document.getElementById('uname').focus()</script>

</html>
