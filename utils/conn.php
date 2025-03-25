<?php
    // Controlla se l'ambiente è di sviluppo o di produzione tramite l'uso del
    // file "isDev", che non viene sincronizzato con git e quindi non sarà 
    // presente nel server
    if(!file_exists("../isDev")) {
        $hostname = "localhost";
        $username = "phpmyadmin";
        $password  = "dietpi";
        $dbname = "Polisportiva";
    } else {
        $hostname = "localhost";
        $username = "root";
        $password  = "";
        $dbname = "Polisportiva";
    }

    $conn = new mysqli($hostname, $username, $password, $dbname) or die("connection error!");

    echo "connection approved!";
?>
