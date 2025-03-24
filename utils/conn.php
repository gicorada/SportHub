<?php

    $hostname = "phpmyadmin.home.ddns.gicorada.com";
    $username = "tommasin";
    $password  = "TommasinPsw";
    $dbname = "Polisportiva";

    $conn = new mysqli($hostname, $username, $password, $dbname) or 
    die("connection error!");




    echo "connection approved!";


?>