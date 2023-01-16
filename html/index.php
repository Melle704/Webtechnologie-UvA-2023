<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Deck building</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body>

<?php include_once "header.php";?>

<div class="box box-row">
    <p>
    取本顔外舞切記区還入氷浦。礎田典著住掲必門財裏栖督暮掲遠売短部。能階採災豆結占恐極覧洲掛験引護理上質。党仰滝手能葉予半道請安統浜中以経。載検将逆家経選効身愕述明吹候毒藤察行電設。後断供界字宅軽証田止衝能界時軍求基務裁根。急野真重万供局国天手暮動見。会更多泳禁更秋労相宮埼致員優大。放没継開要総妻励供空亡職幅密裁対条。

    探胸新藤石五動少防済携載戦以趣直。各条名一者松応来理内差期提容向改変。計政生授別界副績途表音法終月作捨優言。戦中査急援感無住民寒暖億例隆原。高充俊枝月面添千細先金新。聞理済着暮主販潤彼総物毫健党時賀入経法。旧没念岡権告町開弥実全家設譜要務孤人別。舞度他得渡整面夜面青行宏水盟頭月廟井治和。着紙面法意溶刊医報金予意。
    </p>
</div>

<?php if (isset($_SESSION["id"])): ?>
<?php
    require_once "include/db.php";

    $query = mysqli_query($db, "SELECT * FROM users LIMIT 1000");
    $tags = array();
    $now = time();
    $now = new DateTime("@$now");

    while ($row = mysqli_fetch_array($query)) {
        $tag = 'href="/profile.php?id=' . $row["id"] . '">' . $row["uname"] . '</a>';

        // TODO: different display for admins
        if ($row["uname"] == "admin") {
            $tag = '<a id="admin-online" ' . $tag;
        } else {
            $tag = '<a id="user-online" ' . $tag;
        }

        $last_activity = $row["last_activity"];
        $last_activity = new DateTime("$last_activity");

        $dt = $now->diff($last_activity);
        $mins_logged_in = $dt->days * 24 * 60;
        $mins_logged_in += $dt->h * 60;
        $mins_logged_in += $dt->i;

        // fake users online xdd
        if ($mins_logged_in < 10 || $row["uname"] == "admin" || $row["uname"] == "nicolas") {
            array_push($tags, $tag);
        }
    }

    $seperated_tags = implode(", ", $tags);
?>
<?php if (count($tags) > 1): ?>
<div class="box">
    <div class="box-row box-light">
        <b>Users online</b>
    </div>
    <div class="box-row users-online">
        <?php echo $seperated_tags; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php include_once "footer.php"; ?>

<?php if (isset($_SESSION["id"])): ?>
<script src="/js/common.js"></script>
<script>
    update_datetime();
    window.setInterval(update_datetime, 1000);
</script>
<?php endif; ?>

</body>

</html>
