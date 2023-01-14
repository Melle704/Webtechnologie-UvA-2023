<?php

include_once "common.php";

session_start();

// ensure you can't reach the registration or login page if you're logged in
if (isset($_SESSION["id"])) {
    home();
}

// ensure it's form submissions
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
        ["Password contains invalid characters", alphanumeric_plus_plus($passwd1)],
        ["Confirmation password is too long", strlen($passwd2) < 500],
        ["Confirmation Password contains invalid characters", alphanumeric_plus_plus($passwd2)],
        ["Name is too long", strlen($username) < 25],
        ["Name must be alphanumeric", ctype_alnum($username)],
        ["Date format is incorrect", checkdate($month, $day, $year)],
        ["You can't be the oldest person alive", $year > 1900],
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
        ["Password contains invalid characters", alphanumeric_plus_plus($passwd)],
    );

    $user = find_user($db, $username);

    if (!$user) {
        reload_err("Incorrect username and/or password");
    }

    if (!password_verify($passwd, $user["passwd"])) {
        reload_err("Incorrect username and/or password");
    }

    $_SESSION["id"] = $user["id"];
    $_SESSION["uname"] = $user["uname"];
    $_SESSION["last_activity"] = new DateTime($user["last_activity"]);
    $_SESSION["stay_logged"] = $_POST["stay_logged"] == "1";

    update_user_activity($db, $user["id"]);
    home();
}
