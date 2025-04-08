<?php
include "../utils/conn.php";
include "../utils/verifyAndStartSession.php";

$CF = $_SESSION["CF"];

$query = "SELECT Nome, Cognome, Email, TipoAtleta, TipoPersonale, SportPraticato
			FROM PERSONA P
			WHERE CF = ?;";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $CF);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

foreach($result as $row){
    var_dump($row);
}
?>