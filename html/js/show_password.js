function show_password() {
    var x = document.getElementById("password1");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }

    var y = document.getElementById("password2");
    if (y.type === "password") {
        y.type = "text";
    } else {
        y.type = "password";
    }
}
