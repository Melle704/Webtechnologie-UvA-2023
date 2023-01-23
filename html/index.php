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

<?php if (isset($_SESSION["id"])): ?>
<div class="box">
    <div class="box-row box-light">
        <b>Chatbox</b>
    </div>
    <div class="box-row" style="height: 10rem;">
        <div class="chatbox-msgs" id="chatbox"></div>
    </div>
    <div class="box-row" style="padding-top: 0;">
        <div class="chatbox">
            <input id="chatbox-message" type="text" name="msg" maxlength="200">
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_SESSION["id"])): ?>
<?php
    $sql = "SELECT * FROM cards WHERE NOT layout='art_series' AND NOT layout='token' ORDER BY RAND() LIMIT 3";
    $cards = query_execute($db, $sql);
?>
<div class="box">
    <div class="box-row box-light">
        <b>Three random cards</b>
    </div>
    <center>
        <?php foreach ($cards as $card):
            $card_front = $card["image"];
            if ($card_front == NULL) {
                $card_front = "https://mtgcardsmith.com/view/cards_ip/1674397095190494.png?t=014335";
            }
        ?>
            <img src="<?= $card["image"] ?>" alt="<?= $card["name"] ?>" width="300px" border-radius="15px"/>
        <?php endforeach ?>
    </center>
</div>
<?php endif; ?>

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
        if (isset($row["role"]) && $row["role"] == "admin") {
            $tag = '<a id="admin-user" ' . $tag;
        } else {
            $tag = '<a id="default-user" ' . $tag;
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

<?php if (isset($_SESSION["id"])): ?>
<script>
let message_box = document.getElementById("chatbox-message");
let chatbox = document.getElementById("chatbox");

// enable message listener that checks new messages every 0.5 seconds
let message_requester = window.setInterval(request_messages, 500);

// reset queue of new messages and request message log
(async function() {
    await checked_fetch("/broadcast_message.php?action=reset");
    await request_messages();
})();

message_box.addEventListener("keydown", async function(keypress) {
    if (keypress.code == "Enter" && message_box.value != "") {
        // disable message listener
        window.clearInterval(message_requester);

        // send message to server
        await checked_fetch("/broadcast_message.php?action=send", {
            method: "POST",
            body: message_box.value,
            headers: { "Content-Type": "text/plain; charset=UTF-8" }
        });

        // get local user data
        let username = "<?php echo $_SESSION["uname"]; ?>";
        let user_type = "<?php echo $_SESSION["role"]; ?>-user";

        // generate message html layout
        let message = `\n\t\t`
                    + `<span class="message">`
                    + `<b class="message-content" id="${user_type}">${username}</b>`
                    + `<div class="message-content">: ${message_box.value}</div>`
                    + `</span>`;

        // add message to chatbox
        chatbox.innerHTML += message;

        // scroll chatbox down
        chatbox.scrollTop = chatbox.scrollHeight;

        // clear message box
        message_box.value = "";

        // send request for new messages, clearing the unseen message log
        await checked_fetch("/broadcast_message.php?action=receive");

        // re-enable message listener
        message_request = window.setInterval(request_messages, 500);
    }
});

async function request_messages() {
    let body = await checked_fetch("/broadcast_message.php?action=receive");

    if (body != "") {
        // append message to chatbox
        chatbox.innerHTML += body;

        // scroll chatbox down
        chatbox.scrollTop = chatbox.scrollHeight;
    }
}

async function checked_fetch(resource, options = {}) {
    var failed = false;
    let request = await fetch(resource, options)
        .then(v => v, _ => { failed = true });

    if (failed) {
        return "";
    }

    if (request.status == 500) {
        return ""
    }

    if (request.status == 200) {
        return await request.text();
    }

    window.location.replace("/index.php");
}
</script>
<?php endif; ?>

<?php include_once "footer.php"; ?>

</body>

</html>
