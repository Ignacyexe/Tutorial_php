<?php

    session_start();

    if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $connect = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($connect -> connect_errno != 0) {
        echo "Error: ".$connect -> connect_errno;
    } else {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $password = htmlentities($password, ENT_QUOTES, "UTF-8");

        $sql = ;

        if ($result = @$connect -> query(
            sprintf( "SELECT * FROM uzytkownicy WHERE user='%s' AND pass='%s'",
            mysqli_real_escape_string($connect, $login),
            mysqli_real_escape_string($connect, $password)
            )
        ))
        {
            $user = $result -> num_rows;
            if ($user > 0) {

                $_SESSION['logged'] = true;

                $row = $result -> fetch_assoc();
                $_SESSION['id'] = $row['id'];
                $_SESSION['user'] = $row['user'];
                $_SESSION['drewno'] = $row['drewno'];
                $_SESSION['kamien'] = $row['kamien'];
                $_SESSION['zboze'] = $row['zboze'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['dnipremium'] = $row['dnipremium'];

                unset($_SESSION['error']);
                $result->free_result();

                header('Location: gra.php');
            } else {
                $_SESSION['error'] = '<span style="color:red;">Incorrect login or password</span>';
                header('Location: index.php');
            }
        }

    $connect -> close();
    }
