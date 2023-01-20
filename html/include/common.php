<?php

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

function validate_predicates(...$predicates) {
    foreach ($predicates as [$msg, $predicate]) {
        if (!$predicate) {
            reload_err($msg);
        }
    }
}

function alphanumeric_plus_plus($s) {
    return preg_match("/^[\w_@.\/#!%^$?*&+-`~]*$/", $s);
}

function query_execute($db, $sql, $types="", ...$vars) {
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    if ($types != "") {
        mysqli_stmt_bind_param($stmt, $types, ...$vars);
    }
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);

    $rows = [];
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($rows, $row);
    }

    mysqli_stmt_close($stmt);
    return $rows;
}

function find_user($db, $username) {
    $sql = "SELECT * FROM users WHERE uname=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($query);
    mysqli_stmt_close($stmt);
    return $user;
}

function find_user_by_uid($db, $uid) {
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($query);
    mysqli_stmt_close($stmt);
    return $user;
}

function user_create($db, $username, $email, $dob, $passwd) {
    $sql = "INSERT INTO users (uname, email, dob, passwd, last_activity)
            VALUES (?, ?, ?, ?, now())";

    $stmt = mysqli_stmt_init($db);
    $hash = password_hash($passwd, PASSWORD_BCRYPT);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $dob, $hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function update_user_activity($db, $uid) {
    $sql = "UPDATE users SET last_activity=now() WHERE id=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "SELECT last_activity FROM users WHERE ID=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $query = mysqli_fetch_assoc($query)["last_activity"];
    $_SESSION["last_activity"] = new DateTime($query);
    mysqli_stmt_close($stmt);
}

function file_type($file) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_buffer($finfo, $file);
    $mime = substr($mime, 0, strlen("image"));
    return $mime;
}

function format_eur($price) {
    return "€" . number_format($price, 2, ",", ".");
}
