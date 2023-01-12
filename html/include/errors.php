<?php

if (isset($_GET["error"])) {
    $err = str_replace('"', "", $_GET["error"]);

    $msg = <<<EOD
    <div class="box">
        <div class="box-row box-light">
            <b>Error</b>
        </div>

        <div class="box-row">
            $err :(
        </div>
    </div>

    EOD;

    echo $msg;
}
