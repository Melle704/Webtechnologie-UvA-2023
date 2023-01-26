<?php

session_start();

// ensure you can't reach the forum page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}
?>
<div class="box">
    <div class="box-row box-light">
        <div class="post-title box-title">
            <a href="/forum.php">Magic the Gathering forum</a>
        </div>
        <p>
            A space to ask questions and discuss Magic!
            <br>
            <a href="forumrules.php">Forum rules</a>
        </p>
    </div>

    <div class="box-row">
<?php
include_once "include/common.php";
include_once "include/db.php";

$threads = query_execute_unsafe($db, "SELECT * FROM forum_threads ORDER BY date LIMIT 5");

foreach ($threads as $thread):
    $date = format_datetime($thread["date"]);

    $thread_id = $thread["id"];
    $sql = "SELECT SUM(thread_id=$thread_id) FROM forum_posts";
    $comment_count = query_execute_unsafe($db, $sql)[0][0];
?>
        <div class="preview-box">
            <a href="/post.php?id=<?= $thread_id ?>">
                <div class="preview-title"><b><?= $thread["title"] ?></b></div>
                <div class="bottom-text">
                    <p class="post-timestamp"><?= $date ?></p>
                    <p class="comment-count"><?= $comment_count ?> comments</p>
                </div>
            </a>
        </div>
<?php endforeach ?>
    </div>
</div>
