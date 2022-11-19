<?php

    session_start();

    if (!isset($_SESSION['register_success'])) {
        header('Location: index.php');
        exit();
    } else {
        unset($_SESSION['register_success']);
    }

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome +)</title>
</head>
<body>
    <h1>Your registration is complete. Welcome to operation SMILE.</h1> <br>
    <a href="index.php">You can now login into page</a> <br>
</body>
</html>