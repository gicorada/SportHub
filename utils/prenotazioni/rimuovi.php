<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$ruoli = $_SESSION["ruoli"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = $_POST["prenotazioneRimuovi"];
	$CF = $_SESSION["CF"];

	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		// Controlla se il campo è già prenotato in quell'intervallo di tempo
		$queryPrenotato = "SELECT Prenotante FROM PRENOTAZIONE WHERE ID = ?";
		$stmtPrenotato = $conn->prepare($queryPrenotato);
		$stmtPrenotato->bind_param("s", $id);
		$stmtPrenotato->execute();
		$resultPrenotato = $stmtPrenotato->get_result();
		$rowPrenotato = $resultPrenotato->fetch_assoc();
		if ($rowPrenotato['Prenotante'] != $CF) {
			echo "<script>alert('Non puoi rimuovere una prenotazione non tua!');</script>";
			exit();
		}
		$stmtPrenotato->close();
	}

	// Prepara la query
	$query = "DELETE FROM PRENOTAZIONE WHERE ID = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $id);

	// Esegui la query
	if ($stmt->execute()) {
		echo "<script>alert('Prenotazione rimossa con successo!');</script>";
		header("Location: /pages/prenotazioni/prenota.php");
		exit();
	} else {
		echo "<script>alert('Errore durante la rimozione della prenotazione.');</script>";
	}

	// Chiudi la connessione
	$stmt->close();
}