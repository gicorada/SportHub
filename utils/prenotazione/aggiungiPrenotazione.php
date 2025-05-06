<?php 
include "../conn.php";
include "../verifyAndStartSession.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $campo = htmlentities($_POST["field"]);
    $data = htmlentities($_POST["date"]);

    $prenotante = $_SESSION["CF"];


    $query = "INSERT INTO PRENOTAZIONE (Prenotante, Campo, DataInizio) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $prenotante, $campo, $data);
    $stmt->execute();

    if($stmt->affected_rows == 1){
        echo "Prenotazione aggiunta con successo!";
    } else {
        die("Errore durante l'aggiunta della prenotazione:");
    }
}
?>