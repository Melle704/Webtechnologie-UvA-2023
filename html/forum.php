<?php

session_start();

include_once "include/common.php";
include_once "include/db.php";

$user_id = $_SESSION["id"];

// If the post is submitted.
if (isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $text = htmlspecialchars(trim($_POST["content"]));

    // Make sure the input is valid.
    validate_predicates(["Title should be at least 2 characters", strlen($title) >= 2]);
    validate_predicates(["Title should not exceed 124 characters", strlen($title) <= 100]);
    validate_predicates(["Content should not exceed 4096 characters", strlen($text) <= 4096]);

    // Insert the new post into the sql database.
    $sql = "INSERT INTO forum_threads (user_id, title, thread_content) VALUES (?, ?, ?)";
    query_execute($db, $sql, "iss", $user_id, $title, $text);

    // Reload the page.
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Forum</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">
    <link rel="stylesheet" type="text/css" href="/css/forum.css">
</head>

<body>
    <?php include_once "header.php";?>

    <?php include_once "forum_overview.php";?>

    <div class="box box-row">
        <div class="create-title">
            Create a post!
        </div>
        <form id="new-thread-form" class="form" method="post">
            <textarea class="textarea-title" name="title" rows="1" maxlength="100" placeholder="Title"></textarea>
            <textarea class="textarea-content" name="content" maxlength="4096" placeholder="Text (optional)"></textarea>
            <input type="submit" name="submit" value="Create post">
        </form>
    </div>

    <?php include_once "footer.php"; ?>
</body>

</html>
