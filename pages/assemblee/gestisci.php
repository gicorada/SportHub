<!--

QUI CI VANNO
CALENDARIO CON ASSEMBLEE
FORM AGGIUNTA ASSEMBLEA

-->





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Assemblee</title>
    <link rel="stylesheet" href="../../style/base.css">
</head>

<body>
    

    <form action="" method="post">
        <label for="data">Data Assemblea:</label>
        <input type="date" id="data" name="data" required>

        <label for="ora">Ora Assemblea:</label>
        <input type="time" id="ora" name="ora" required>

        <input type="submit" value="Aggiungi Assemblea">
    </form>


    <a href="visualizzaAssemblee.php"><button>Visualizza Assemblee</button></a>


</body>
</html>


<?php
    include "../../utils/conn.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = $_POST["data"];
        $ora = $_POST["ora"];

        $query = "INSERT INTO assemblee (data, ora) VALUES (?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $data, $ora);

        $stmt->execute();

        $res = $stmt->get_result();

        if($res->affected_rows == 1) {
            echo "Assemblea aggiunta con successo!";
        } else {
            echo "Errore nell'aggiunta dell'assemblea.";
            header("Location: ../gestisciAssemblee.php");
        }
    }
