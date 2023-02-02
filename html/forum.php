<?php

include_once "include/common.php";
include_once "include/db.php";

session_start();

// Ensure you can't reach the forum page if you're not logged in.
if (!isset($_SESSION["id"])) {
    header("Location: /");
    exit;
}

$user_id = $_SESSION["id"];

// If the post is submitted.
if (isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $text = htmlspecialchars(trim($_POST["content"]));

    // Make sure the input is valid.
    validate_predicates(
        ["Title should be at least 2 characters", strlen($title) >= 2],
        ["Title should not exceed 124 characters", strlen($title) <= 100],
        ["Content should not exceed 4096 characters", strlen($text) <= 4096]
    );

    // Insert the new post into the sql database.
    $sql = "INSERT INTO forum_threads (user_id, title, thread_content) VALUES (?, ?, ?)";
    query_execute($db, $sql, "iss", $user_id, $title, $text);

    // Reload the page.
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}

// Sort by most comments on standard.
$sort_by = "comments-desc";
if (isset($_GET["sort_by"])) {
    $sort_by = $_GET["sort_by"];
}

// Create the query for how to sort.
$query = "SELECT * FROM forum_threads";
if ($sort_by === "comments") {
    $query .= " ORDER BY comments";
} else if ($sort_by === "comments-desc") {
    $query .= " ORDER BY comments DESC";

} else if ($sort_by === "date") {
    $query .= " ORDER BY date";
} else if ($sort_by === "date-desc") {
    $query .= " ORDER BY date DESC";
}

// Show a maximum of 20 posts.
$query .= " LIMIT 20";

$threads = query_execute_unsafe($db, $query);
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
    <script>
// update's textarea size on text input
function grow_box(self) {
    if (self.scrollHeight > 84) {
        self.style.height = "99px";
        self.style.height = (self.scrollHeight + 4) + "px";
    }
}
    </script>
</head>

<body>
<?php include_once "header.php";?>

<div class="box">
    <div class="box-row box-light" style="margin-bottom: -10px">
        <div class="post-title box-title">
            <a href="/forum">Magic the Gathering forum</a>
        </div>
        <p>
            A space to ask questions and discuss Magic!
            <br>
            <a href="/rules">Forum rules</a>
        </p>
        <div class="search-sort form">
            <form action="/forum" method="GET">
                <input name="search" type="text">
                <input type="submit" value="Search content">

                <select name="sort_by" style="float: right" onchange="this.form.submit()">
                    <option value="comments-desc" <?= ($sort_by === "comments-desc") ? "selected" : "" ?>>
                        Sort by most comments
                    </option>
                    <option value="comments" <?= ($sort_by === "comments") ? "selected" : "" ?>>
                        Sort by least comments
                    </option>
                    <option value="date-desc" <?= ($sort_by === "date-desc") ? "selected" : "" ?>>
                        Sort by newest
                    </option>
                    <option value="date" <?= ($sort_by === "date") ? "selected" : "" ?>>
                        Sort by oldest
                    </option>
                </select>
            </form>
        </div>
    </div>
    <div class="box-row">
<?php
// Search thrue all the threads.
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$filtered_threads = [];
foreach ($threads as $thread) {
    if (strpos(strtolower($thread["title"]), strtolower($search)) !== false) {
        $filtered_threads[] = $thread;
    }
}
$threads = $filtered_threads;

foreach ($threads as $thread):
    $date = format_datetime($thread["date"]);

    $thread_id = $thread["id"];
    $sql = "SELECT SUM(thread_id=$thread_id) FROM forum_posts";

    $comment_count = $thread["comments"];
?>
        <div class="preview-box">
            <a href="/post?id=<?= $thread_id ?>">
                <div class="preview-title"><b><?= $thread["title"] ?></b></div>
                <div class="bottom-text">
                    <p class="post-timestamp"><?= $date ?></p>
                    <p class="comment_count"><?= $comment_count ?> comments</p>
                </div>
            </a>
        </div>
<?php endforeach ?>
    </div>
</div>

<div class="box box-row">
    <div class="create-title">
        Create a post!
    </div>
    <form id="new-thread-form" class="form" method="post">
        <textarea class="textarea-title" name="title" rows="1" maxlength="100" placeholder="Title"></textarea>
        <textarea
            class="textarea-content"
            name="content"
            maxlength="4096"
            oninput="grow_box(this)"
            placeholder="Text (optional)"
        ></textarea>
        <input type="submit" name="submit" value="Create post">
    </form>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
