<?php

include_once "include/common.php";

function send_message($db, $uid, $text) {
    $sql = "INSERT INTO messages (uid, text, date) VALUES (?, ?, now())";
    $stmt = mysqli_stmt_init($db);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "is", $uid, $text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// retrieve latest 150 messages
function retrieve_messages($db) {
    $sql = "SELECT * FROM messages ORDER BY date LIMIT 150";
    $query = mysqli_query($db, $sql);
    $entries = array();

    while ($row = mysqli_fetch_array($query)) {
        array_push($entries, $row);
    }

    return $entries;
}

function format_message($db, $message) {
    $user = find_user_by_uid($db, $message["uid"]);

    $s = "\n\t\t<span class=\"message\">";

    // add a link to the profile's of other users
    if ($_SESSION["id"] != $message["uid"]) {
        $s .= '<a target="_blank" href="/profile.php?id=' . $message["uid"]. '">';
    }

    $s .= '<b class="message-content">'
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
