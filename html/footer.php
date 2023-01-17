<div class="box box-row">
    Credits to
    <b>Nicolas Mazzon</b>,
    <b>Sebastian Gielens</b>,
    <b>Servaes Koning</b>,
    <b>Ceylan Siegertsz</b> and
    <b>Kas Visser</b>
    <br>
    <a href="/about.php">About us</a>
    /
    <a href="/cookies.php">Cookie policy</a>
    <br>
</div>

<?php if (isset($_SESSION["id"])): ?>
<script src="/js/common.js"></script>
<script>
    update_datetime();
    window.setInterval(update_datetime, 1000);
</script>
<?php endif; ?>
