<?php

include "../utils/conn.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="gestisci.php" method="post">
        <input type="number" name="numProt">
        <input type="date" name="" id="">
        <input type="date" name="dataAtto">
        <input type="text" name="Oggetto">
        <input type="text" name="odg">
        <input type="text" name="testo">
    </form>
</body>
</html>