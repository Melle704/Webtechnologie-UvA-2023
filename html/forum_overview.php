<?php

session_start();

// Ensure you can't reach the forum page if you're not logged in.
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

include_once "include/common.php";
include_once "include/db.php";

// Sort by most comments on standard.
$sortBy = "comments-desc";
if (isset($_GET["sortBy"])) {
  $sortBy = $_GET["sortBy"];
}

// Create the query for how to sort.
$query = "SELECT * FROM forum_threads";
if ($sortBy === "comments") {
    $query .= " ORDER BY comments";
} else if ($sortBy === "comments-desc") {
    $query .= " ORDER BY comments DESC";

} else if ($sortBy === "date") {
    $query .= " ORDER BY date";
} else if ($sortBy === "date-desc") {
    $query .= " ORDER BY date DESC";
}
// Show a maximum of 20 posts.
$query .= " LIMIT 20";

$threads = query_execute_unsafe($db, $query);
?>

<div class="box">
    <div class="box-row box-light" style="margin-bottom: -10px">
        <div class="post-title box-title">
            <a href="/forum.php">Magic the Gathering forum</a>
        </div>
        <p>
            A space to ask questions and discuss Magic!
            <br>
            <a href="rules.php">Forum rules</a>
        </p>
        <div class="search-sort form">
            <input type="text" id="search-input">
            <input type="button" id="search-button" value="Search content"></button>

            <select id="sort-select" style="float: right">
                <option value="comments-desc" <?= ($sortBy === "comments-desc") ? "selected" : "" ?>>Sort by most comments</option>
                <option value="comments" <?= ($sortBy === "comments") ? "selected" : "" ?>>Sort by least comments</option>
                <option value="date-desc" <?= ($sortBy === "date-desc") ? "selected" : "" ?>>Sort by newest</option>
                <option value="date" <?= ($sortBy === "date") ? "selected" : "" ?>>Sort by oldest</option>
            </select>
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
        ?>
        <?php foreach ($threads as $thread): ?>
        <?php
            $date = format_datetime($thread["date"]);

            $thread_id = $thread["id"];
            $sql = "SELECT SUM(thread_id=$thread_id) FROM forum_posts";

            $comment_count = $thread["comments"];
        ?>
        <div class="preview-box">
            <a href="/post.php?id=<?= $thread_id ?>">
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

<script>
    const select = document.getElementById("sort-select");
    select.addEventListener("change", function() {
        window.location.href = "/forum.php?sortBy=" + select.value;
    });

    const input = document.getElementById("search-input");
    const button = document.getElementById("search-button");
    button.addEventListener("click", function() {
        window.location.href = "/forum.php?sortby=" + select.value + "&search=" + input.value;
    });
</script>
