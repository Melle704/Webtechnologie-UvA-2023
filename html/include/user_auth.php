<?php

// verbose error reporting (remove in live version)
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
        ["Passwords don't match", $passwd1 == $passwd2],
        ["Password is too long", strlen($passwd1) < 500],
        ["Confirmation password is too long", strlen($passwd2) < 500],
        ["Name is too long", strlen($username) < 25],
        ["Name must be alphanumeric", ctype_alnum($username)],
        ["Date format is incorrect", checkdate($month, $day, $year)],
        ["Email is too long", strlen($email) < 30],
        ["Email format is incorrect", filter_var($email, FILTER_VALIDATE_EMAIL)],
        ["User '$username' already exists", !find_user($db, $username)]
    );

    user_create($db, $username, $email, "$year-$month-$day", $passwd1);
    home();
}

// handle login button submit from `login.php`
if ($_GET["action"] == "login") {
    $username = trim($_POST["uname"]);
    $passwd = trim($_POST["passwd"]);

    require_once "db.php";

    validate_not_empty(
        ["username", $username],
        ["password", $passwd],
    );

    validate_predicates(
        ["Name is too long", strlen($username) < 25],
        ["Name must be alphanumeric", ctype_alnum($username)],
        ["Password is too long", strlen($passwd) < 500],
    );

    $user = find_user($db, $username);

    if (!$user) {
        reload_err("Incorrect username and/or password");
    }

    if (!password_verify($passwd, $user["passwd"])) {
        reload_err("Incorrect username and/or password");
    }

    home();
}
