<?php

session_start();

//checking if user is logged, if session is still alive, then php is relocating user to gra.php
if ((isset($_SESSION['logged'])) && $_SESSION['logged']) {
    header('Location: gra.php');
    exit();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smiley</title>

    <p style="text-align: right">
        <a href="registration.php">Register NOW!</a>
    </p>

    <style>

        h1 {
            animation: color-change 1ms infinite;
        }

        @keyframes color-change {
            0% { color: red; }
            50% { color: blue; }
            100% { color: yellow; }
        }

    </style>
</head>
<body>

<h1 style="text-align: center">get revenge NOW. grab a rifle. destroy everything</h1>
<p style="text-align: center; font-size: 50px">😁😉😋😜😡😶😂😀🤗😏🥱</p>
<br><br>

<form action="login.php" method="post">
    
    Login: <br> <input type="text" name="login"> <br>
    Password: <br> <input type="password" name="password"> <br> <br>
    <input type="submit" value="Log in">
    
</form>

<?php

if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
}

?>

</body>
</html>