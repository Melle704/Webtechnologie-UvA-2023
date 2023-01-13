<?php session_start();?>

<div class="box">
    <div class="box-head">
        <div class="box-row">
            <div class="box-title">
                <span>MAGIC</span> THE GATHERING
            </div>
        </div>
        <div class="box-row box-light">
            <div class="box-left">
                <ul>
                    <li><a href="index.php">Index</a></li>
                    <li><a href="cards.php">Cards</a></li>
                </ul>
            </div>
            <div class="box-right">
                <ul>
                <?php if (isset($_SESSION["id"])): ?>
                    <li><a href="profile.php?id=<?php echo $_SESSION["id"];?>">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php if (isset($_SESSION["uname"])): ?>
        <div class="box-row">
            <div class="box-left">
                Logged in as <b><?php echo $_SESSION["uname"];?></b>
            </div>
            <div class="box-right">
                <?php echo date("d M Y") . " at " . date("g:i:s a");?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
