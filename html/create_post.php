<?php

session_start();

include_once "include/common.php";
include_once "include/db.php";

$user_id = $_SESSION["id"];

if (isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $text = htmlspecialchars(trim($_POST["content"]));

    // Make sure the input is valid.
    validate_predicates(["Title should be at least 2 characters", strlen($title) >= 2]);
    validate_predicates(["Title should not exceed 124 characters", strlen($title) <= 100]);
    validate_predicates(["Content should not exceed 4096 characters", strlen($text) <= 4096]);

    $sql = "INSERT INTO forum_threads (user_id, title, thread_content) VALUES (?, ?, ?)";
    query_execute($db, $sql, "iss", $user_id, $title, $text);

    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}
?>

<div class="box box-row">
    <div class="create-title">
        Create a post!
    </div>
    <form id="new-thread-form" method="post">
        <textarea class="textarea-title" name="title" rows="1" maxlength="100" placeholder="Title"></textarea>
        <textarea class="textarea-content" name="content" maxlength="4096" placeholder="Text (optional)"></textarea>
        <input type="submit" name="submit">
    </form>
</div>