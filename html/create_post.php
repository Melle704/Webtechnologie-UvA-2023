<?php

session_start();

include_once "include/common.php";
include_once "include/db.php";

?>
<div class="box box-row">
    <div class="create-title">
        Create a post!
    </div>
    <textarea class="textarea-title" rows="1" maxlength="124" placeholder="Title"></textarea>

    <textarea maxlength="4096" placeholder="Text (optional)"></textarea>

    <input type="submit"></input>
</div>
<?php