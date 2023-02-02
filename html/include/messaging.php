<?php

include_once "include/common.php";

function send_message($db, $uid, $text) {
    mysqli_autocommit($db, false);

    $sql = "INSERT INTO messages (uid, text, date) VALUES (?, ?, now())";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "is", $uid, $text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $query = mysqli_query($db, "SELECT LAST_INSERT_ID()");
    $row = mysqli_fetch_array($query);

    mysqli_commit($db);
    mysqli_autocommit($db, true);

    return $row["LAST_INSERT_ID()"];
}

// retrieve latest 150 messages
function retrieve_messages($db) {
    return query_execute_unsafe($db, "SELECT * FROM messages ORDER BY date LIMIT 150");
}

function format_message($db, $message) {
    $user = find_user_by_uid($db, $message["uid"]);
    $role = isset($user["role"]) ? $user["role"] : "default";

    $s = "\n\t\t<span class=\"message\">";

    // add a link to the profile's of other users
    if ($_SESSION["id"] != $message["uid"]) {
        $s .= '<a target="_blank" href="/profile?id=' . $message["uid"]. '">';
    }

    $s .= '<b class="message-content" id="'
        . $role
        . "-user"
        . '">'
        . $user["uname"]
        . "</b>";

    // add a link to the profile's of other users
    if ($_SESSION["id"] != $message["uid"]) {
        $s .= "</a>";
    }

    $s .= '<div class="message-content">'
        . ": "
        . $message["text"]
        . "</div>"
        . "</span>";

    return $s;
}
