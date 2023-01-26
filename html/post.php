<?php

session_start();

// ensure you can't reach the post page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    $redirect_title="Incorrect posts";
    $redirect_msg="You tried to visit a thread that doesn't exist.";
    include_once "include/redirect.php";

    // wait two seconds before refreshing
    header("Refresh: 2; url=/index.php");
    exit;
}

include_once "include/common.php";
include_once "include/db.php";

$thread_id = intval($_GET["id"]);
$thread = query_execute_unsafe($db, "SELECT * from forum_threads WHERE id=$thread_id");

if (count($thread) != 1) {
    $redirect_title="Incorrect posts";
    $redirect_msg="You tried to visit a thread that doesn't exist.";
    include_once "include/redirect.php";

    // wait two seconds before refreshing
    header("Refresh: 2; url=/index.php");
    exit;
}

$thread = $thread[0];
$user_id = $_SESSION["id"];

if (isset($_POST["submit"])) {
    $text = htmlspecialchars(trim($_POST["text"]));

    validate_predicates(["Messages should be of at least 10 characters", strlen($text) >= 10]);

    $sql = "INSERT INTO forum_posts (thread_id, user_id, text) VALUES (?, ?, ?)";
    query_execute($db, $sql, "iis", $thread_id, $user_id, $text);

    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}

$user = query_execute_unsafe($db, "SELECT * FROM users where id=$user_id")[0];

$sql = "SELECT * FROM forum_posts WHERE thread_id=$thread_id ORDER BY date LIMIT 50";
$posts = query_execute_unsafe($db, $sql);
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
    <link rel="stylesheet" type="text/css" href="/css/forum.css">
</head>

<body>
<?php include_once "header.php";?>

<div class="box box-row">
    <div class="post-title"><?= $thread["title"] ?></div>
    <div class="bottom-text">
        <p><?= $user["uname"] ?> - <?= format_datetime($thread["date"]) ?></p>
    </div>
</div>

<?php foreach ($posts as $post):
    $post_user_id = $post["user_id"];
    $post_user = query_execute_unsafe($db, "SELECT * FROM users where id=$post_user_id")[0];

    // get profile picture of user
    $profile_pic = @file_get_contents("./img/user" . $post_user_id . ".raw");
    $profile_pic_type = @file_get_contents("./img/user" . $post_user_id . ".info");

    if (!$profile_pic) {
        $profile_pic = file_get_contents("./img/sample.raw");
        $profile_pic_type = "image/png";
    }
?>
<div class="box box-row">
    <div class="profile-pic">
        <a class="user" href="/profile.php?id=<?=$post_user_id?>"><?= $post_user["uname"] ?></a>
        <img src="<?= "data:$profile_pic_type;base64,$profile_pic" ?>">
    </div>
    <div class="comment">
        <div class="comment-header">
            <p><?= format_datetime($post["date"]) ?></p>
        </div>
        <p class="comment-content"><?= $post["text"] ?></p>
    </div>
</div>
<?php endforeach ?>

<?php include_once "include/errors.php";?>

<div class="box box-row">
    <form action="/post.php?id=<?= $thread_id ?>" method="post">
        <textarea name="text" style="margin-top: 0;"></textarea>
        <input type="submit" name="submit" value="Add comment">
    </form>
</div>

<?php include_once "footer.php"; ?>

</body>

<!-- scroll down to bottom of page if we have an error -->
<?php if (isset($_GET["error"])): ?>
<script>
let body_height = document.scrollingElement.scrollHeight;
document.scrollingElement.scrollTo({ top: body_height })
</script>
<?php endif; ?>

</html>
