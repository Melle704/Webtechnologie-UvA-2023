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
    if ($query === false) {
        return false;
    }

    $rows = array();
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($rows, $row);
    }

    mysqli_stmt_close($stmt);
    return $rows;
}

function query_execute_unsafe($db, $sql) {
    $query = mysqli_query($db, $sql);
    $rows = array();

    if (!$query) {
        return $rows;
    }

    while ($row = mysqli_fetch_array($query)) {
        array_push($rows, $row);
    }

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

function logout_user_on_inactivity($db) {
    $now = time();
    $now = new DateTime("@$now");

    $dt = $now->diff($_SESSION["last_activity"]);
    $mins_logged_in = $dt->days * 24 * 60;
    $mins_logged_in += $dt->h * 60;
    $mins_logged_in += $dt->i;

    // logout user after 10 minutes of inactivity
    if ($mins_logged_in >= 10 && !$_SESSION["stay_logged"]) {
        session_destroy();
        header("Location: /index.php");
        exit;
    }

    $sql = "UPDATE users SET last_activity=now() WHERE id=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "SELECT last_activity FROM users WHERE id=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $query = mysqli_fetch_assoc($query)["last_activity"];
    $_SESSION["last_activity"] = new DateTime($query);
    mysqli_stmt_close($stmt);
}

function update_user_desc($db, $uid, $desc) {
    $sql = "UPDATE users SET profile_desc=? WHERE id=?";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "si", $desc, $uid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function format_eur($price) {
    if ($price < 0.05) {
        return "€--";
    }
    return "€" . number_format($price, 2, ",", ".");
}

function format_datetime($sql_datetime) {
    return date_format(date_create($sql_datetime), "Y-m-d h:i A");
}
