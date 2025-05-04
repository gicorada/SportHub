<?php
    session_start();
    if(!isset($_SESSION["Email"])){
        session_abort();
        header("Location: ".__DIR__ ."/../login.html");
        exit();
    };
?>