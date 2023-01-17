<div class="box box-row">
    Credits to
    <b>Nicolas Mazzon</b>,
    <b>Sebastian Gielens</b>,
    <b>Servaes Koning</b>,
    <b>Ceylan Siegertsz</b> and
    <b>Kas Visser</b>
    <br>
    <a href="/about.php">About us</a>
    <br>
</div>

<?php if (isset($_SESSION["id"])): ?>
<script>
function datetime() {
    let date = new Date();
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let year = String(date.getFullYear());
    let hour = date.getHours();
    let minute = String(date.getMinutes()).padStart(2, '0');
    let second = String(date.getSeconds()).padStart(2, '0');
    let suffix = "am";

    if (hour == 12) {
        hour = 12;
        suffix = "pm";
    }

    if (hour > 12) {
        hour -= 12;
        suffix = "pm";
    }

    return `${day}/${month}/${year} ${hour}:${minute}:${second} ${suffix}`;
}

function update_datetime() {
    document.getElementById("datetime").innerText = datetime();
}

update_datetime();
window.setInterval(update_datetime, 1000);
</script>
<?php endif; ?>
