<?php

    session_start();

    //checking if any of the inputs are filled, if so code continues to execute
    if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";  //requiring connection from database only for once because of security reasons

    $connect = @new mysqli($host, $db_user, $db_password, $db_name);  //setting up connection with database

    if ($connect -> connect_errno != 0) {
        echo "Error: ".$connect -> connect_errno;   // this line of code is for better for error handling
    } else {
        $login = $_POST['login'];
        $password = $_POST['password'];

        //this line is for sanitising code and preventing from xss attacks
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        if ($result = @$connect -> query(
            sprintf( "SELECT * FROM uzytkownicy WHERE user='%s'",
            mysqli_real_escape_string($connect, $login)
            )
        ))
        {
            $user = $result -> num_rows;
            if ($user > 0)
            {
                $row = $result -> fetch_assoc();

                // testing if password matches with encrypted hash in db
                if (password_verify($password, $row['pass'])) {

                    $_SESSION['logged'] = true;

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

            } else {
                $_SESSION['error'] = '<span style="color:red;">Incorrect login or password</span>';
                header('Location: index.php');
            }
        }

    $connect -> close();
    }
