<?php
    $hostname = "localhost";
    $username = "phpmyadmin";
    $password  = "dietpi";
    $dbname = "Polisportiva";

    $conn = new mysqli($hostname, $username, $password, $dbname) or die("connection error!");

    echo "connection approved!";
?>
