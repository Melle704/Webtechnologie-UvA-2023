function datetime() {
    let date = new Date();
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let year = String(date.getFullYear());
    let hour = date.getHours();
    let minute = String(date.getMinutes()).padStart(2, '0');
    let second = String(date.getSeconds()).padStart(2, '0');
    let suffix = "am";

    if (hour > 12) {
        hour -= 12;
        suffix = "pm";
    }

    let fmt = `${day}/${month}/${year} ${hour}:${minute}:${second} ${suffix}`;


    return fmt;
}

function update_datetime() {
    document.getElementById("datetime").innerText = datetime();
}
