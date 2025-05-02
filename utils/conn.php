<?php
    // Controlla se l'ambiente è di sviluppo o di produzione tramite l'uso del
    // file "isDev", che non viene sincronizzato con git e quindi non sarà 
    // presente nel server
    if(!file_exists(__DIR__ . "/../isDev")) {
        $hostname = "localhost";
        $username = "phpmyadmin";
        $password  = "dietpi";
        $dbname = "Polisportiva";
    } else {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        $hostname = "localhost";
        $username = "polisportiva";
        $password  = "polisportiva";

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Su Windows, MySQL su XAMPP non permette l'uso di database con maiuscole
            $dbname = "polisportiva";
        } else {
            $dbname = "Polisportiva";
        }
    }

    $conn = new mysqli($hostname, $username, $password, $dbname) or die("connection error!");
?>
