var check = function() {
    if (document.getElementById('password1').value ==
      document.getElementById('password2').value) {
      document.getElementById('message').style.color = 'green';
      document.getElementById('message').innerHTML = '✔';
    } else {
      document.getElementById('message').style.color = 'red';
      document.getElementById('message').innerHTML = 'x';
    }
  }