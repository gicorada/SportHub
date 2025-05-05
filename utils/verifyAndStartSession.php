<?php
    session_start();
    if(!isset($_SESSION["Email"])){
        session_abort();
        header("Location: ./login.html");
        exit();
    };
?>