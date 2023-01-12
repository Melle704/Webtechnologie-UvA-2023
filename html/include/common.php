<?php

function home() {
    header("Location: /index.php");
    exit;
}

function reload() {
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

function reload_err($err = "") {
    $redirect = "Location: " . $_SERVER["PHP_SELF"];

    if ($err != "") {
        $redirect = "$redirect?error=\"$err\"";
    }

    header($redirect);
    exit;
}

function validate_not_empty(...$variables) {
    foreach ($variables as [$name, $var]) {
        if (empty($var)) {
            reload_err("Form field '$name' is not set");
        }
    }
}

function validate_predicates(...$variables) {
    foreach ($variables as [$msg, $predicate]) {
        if (!$predicate) {
            reload_err($msg);
        }
    }
}

function user_exists($db, $username) {
    $sql = "SELECT * FROM users WHERE uname = ?;";

    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($query);
    mysqli_stmt_close($stmt);
    return $result;
}

function user_create($db, $username, $email, $dob, $passwd) {
    $sql = "INSERT INTO users (uname, email, dob, passwd) VALUES (?, ?, ?, ?)";
    $hash = password_hash($passwd, PASSWORD_BCRYPT);

    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $dob, $hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
