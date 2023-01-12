<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once "common.php";

// FIXME: check if user is already logged in, returning early

// ensure you can only access `/include/user_auth.php` from a form POST request
if (!isset($_POST["submit"])) {
    return;
}

// handle register button submit from `register.php`
if ($_GET["action"] == "register") {
    $username = trim($_POST["uname"]);
    $passwd1 = trim($_POST["passwd1"]);
    $passwd2 = trim($_POST["passwd2"]);
    $email = strtolower(trim($_POST["email"]));
    $day = intval(trim($_POST["day"]));
    $month = intval(trim($_POST["month"]));
    $year = intval(trim($_POST["year"]));

    require_once "db.php";

    validate_not_empty(
        ["username", $username],
        ["password", $passwd1],
        ["confirmation password", $passwd2],
        ["email", $email],
        ["day", $day],
        ["month", $month],
        ["year", $year]
    );

    validate_predicates(
        ["passwords don't match", $passwd1 == $passwd2],
        ["password is too long", strlen($passwd1) < 500],
        ["confirmation password is too long", strlen($passwd2) < 500],
        ["name is too long", strlen($username) < 25],
        ["name must be alphanumeric", ctype_alnum($username)],
        ["date format is incorrect", checkdate($month, $day, $year)],
        ["email is too long", strlen($email) < 30],
        ["email format is incorrect", filter_var($email, FILTER_VALIDATE_EMAIL)],
        ["user '$username' already exists", !user_exists($db, $username)]
    );

    user_create($db, $username, $email, "$year-$month-$day", $passwd1);
    return_home();
}

// handle login button submit from `login.php`
if ($_GET["action"] == "login") {
    $username = trim($_POST["uname"]);
    $passwd = trim($_POST["passwd"]);
    $stay_logged = $_POST["stay_logged"];

    validate_not_empty(
        ["name", $username],
        ["passwd", $passwd],
        ["stay_logged", $stay_logged]
    );

    validate_predicates(
        ["name is too long", strlen($username) < 25],
        ["name must be alphanumeric", ctype_alnum($username)],
        ["password is too long", strlen($passwd1) < 25],
        ["checkbox must be checked or unchecked", $stay_logged == "0" || $stay_logged == "1"]
    );

    return_home();
}
