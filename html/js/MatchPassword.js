var check = function() {
    if (document.getElementById('password1').value == document.getElementById('password2').value) {
        document.getElementById('message').style.color = 'green';
        //document.getElementById('message').innerText = '✔';
        document.getElementById('message').innerText = '';
    } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerText = 'x';
    }
}
