<?php

    session_start();

// this block of code is made for validating forms
if (isset($_POST['email'])) {

    $everythingIsFine = true;

    $nick = $_POST['nick'];
    if ((strlen($nick) < 3) || (strlen($nick) > 20)) {
        $everythingIsFine = false;
        $_SESSION['e_nick'] = "Nick MUST be between 3 and 20 characters";
    }
    if (!ctype_alnum($nick)) {
        $everythingIsFine = false;
        $_SESSION['e_nick'] = "Nicks MUST have only letters and numbers";
    }


    $email = $_POST['email'];
    $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($emailS, FILTER_VALIDATE_EMAIL) || ($emailS != $email)) {
        $everythingIsFine = false;
        $_SESSION['e_email'] = "TYPE correct email address";
    }

    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    if (strlen($password1) < 8 || strlen($password1) > 24) {
        $everythingIsFine = false;
        $_SESSION['e_password'] = "Password MUST be between 8 and 24 characters";
    }
    if ($password1 != $password2) {
        $everythingIsFine = false;
        $_SESSION['e_password'] = "Given passwords are not the same. FIX IT.";
    }

    // When password is typed then is converted into slow bcrypt hash.
    $password_hash = password_hash($password1, PASSWORD_DEFAULT);

    // Checking if terms were accepted
    if (!isset($_POST['tos'])) {
        $everythingIsFine = false;
        $_SESSION['e_tos'] = "Proceed to sell soul NOW!!!";
    }

    // Checking if captcha was verified with secret key
    $secret = "6LdZNgAjAAAAALwK2n9xMmRo0Mb4W5HTUfxkWC3C";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.
    $_POST['g-recaptcha-response']);

    $response = json_decode($check);

    if (!$response -> success) {
        $everythingIsFine = false;
        $_SESSION['e_bot'] = "Check if you are not a bot!";
    }

    //trying new type of connection to the database
    require_once "connect.php";
    // Cutting off very vulnerable information about server errors with this function
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connect -> connect_errno != 0 ) {
            throw new Exception(mysqli_connect_errno());
        } else {
            // Checking if email already exist in db
            $result = $connect -> query("SELECT id FROM uzytkownicy WHERE email='$email'");

            if (!$result) throw new Exception($connect -> error);

            $how_many_emails = $result -> num_rows;
            if ($how_many_emails > 0) {
                $everythingIsFine = false;
                $_SESSION['e_email'] = "This email already exists! FIX IT";
            }

            // Checking if nickname already exists in db
            $result = $connect -> query("SELECT id FROM uzytkownicy WHERE user='$nick'");

            if (!$result) throw new Exception($connect -> error);

            $how_many_nicks = $result -> num_rows;
            if ($how_many_nicks > 0) {
                $everythingIsFine = false;
                $_SESSION['e_nick'] = "This nickname already exists! FIX IT";
            }



            if ($everythingIsFine) {
                if ($connect -> query("INSERT INTO uzytkownicy VALUES(NULL,'$nick','$password_hash','$email',
                                             100,100,100,now() + INTERVAL 14 DAY)")){
                    $_SESSION['register_success'] = true;
                    header('Location: welcome.php');
                } else {
                    throw new Exception($connect -> error);
                }
            }

            $connect -> close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red">Server error! Come back later</span>';
        //printing type of error when website is in development
        // echo '<br>Type of error: '.$e;
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register NOW!</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script type="text/javascript">
        let onloadCallback = function() {
            alert("grecaptcha is ready!");
        };
    </script>

    <style>

        .error {
            color: red;
            margin-bottom: 10px;
        }

    </style>

</head>
<body>

    <form method="post">
        <div>
            <label>
                Nick: &emsp;&ensp;&nbsp;
                <input type="text" name="nick" placeholder="TYPE your nick"> <br><br>
            </label>
            <?php
                if (isset($_SESSION['e_nick'])) {
                    echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                    unset($_SESSION['e_nick']);
                }
            ?>
        </div>

        <div>
            <label>
                Email: &emsp;&nbsp;
                <input type="text" name="email" placeholder="TYPE your email"> <br><br>
            </label>
            <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                }
            ?>
        </div>

        <div>
            <label>
                Password:
                <input type="password" name="password1" placeholder="TYPE your password"> <br><br>
            </label>
            <?php
            if (isset($_SESSION['e_password'])) {
                echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            }
            ?>
        </div>
        <div>
            <label>
                Type password AGAIN:
                <input type="password" name="password2" placeholder="TYPE your password"> <br><br>
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="tos">
                I accept the terms (proceed to sell soul)
            </label>
                <?php
                if (isset($_SESSION['e_tos'])) {
                    echo '<div class="error">'.$_SESSION['e_tos'].'</div>';
                    unset($_SESSION['e_tos']);
                }
                ?>
        </div>

        <br><br>
        <div class="g-recaptcha" data-sitekey="6LdZNgAjAAAAACFYDDNVyEaM7WYHfffDBglPm11s"></div><br><br>

        <?php
        if (isset($_SESSION['e_bot'])) {
            echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
            unset($_SESSION['e_bot']);
        }
        ?>

        <input type="submit" value="Register NOW">
    </form>

</body>
</html>