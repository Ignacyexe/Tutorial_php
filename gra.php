<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>smile!</title>
</head>
<body>
<?php

    session_start();

    if (!isset($_SESSION['logged'])) {
        header('Location: index.php');
        exit();
    }

    echo "<p>Welcome ".$_SESSION['user']."! [<a href='logout.php'>Log out</a>]<br><br>";

    echo "<p><b>Drewno:</b>".$_SESSION['drewno'];
    echo "<p><b>Kamień:</b>".$_SESSION['kamien'];
    echo "<p><b>Zboże:</b>".$_SESSION['zboze']."<br><br>";
    echo "<p><b>Email:</b>".$_SESSION['email']."<br>";
    echo "<p><b>Dni premium:</b>".$_SESSION['dnipremium'];

?>
</body>
</html>