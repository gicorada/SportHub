<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$ruoli = $_SESSION["ruoli"];
if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
	die("Permessi insufficienti");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$inizio = $_POST["inizio"];
	$fine = $_POST["fine"];
	$odg = $_POST["odg"];
	$descrizione = $_POST["descrizione"];

	$CF = $_SESSION["CF"];

	if($inizio > $fine) {
		echo "<script>alert('La data di inizio deve essere precedente alla data di fine.');</script>";
		exit();
	}

	// Prepara la query
	$query = "INSERT INTO ASSEMBLEA (Data, DataFine, Descrizione, ODG, Convocatore) VALUES (?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("sssss", $inizio, $fine, $descrizione, $odg, $CF);

	// Esegui la query
	if ($stmt->execute()) {
		echo "<script>alert('Assemblea aggiunta con successo!');</script>";
		header("Location: /pages/assemblee/gestisci.php");
		exit();
	} else {
		echo "<script>alert('Errore durante l\'aggiunta dell\'assemblea.');</script>";
	}

	// Chiudi la connessione
	$stmt->close();
}