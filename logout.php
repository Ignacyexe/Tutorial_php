<?php

    // When button logout is clicked session is terminated
    session_start();

    session_unset();

    header('Location: index.php');