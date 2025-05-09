<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$ruoli = $_SESSION["ruoli"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$inizio = $_POST["inizio"];
	$fine = $_POST["fine"];
	$campo = $_POST["campo"];
	$attivita = $_POST["attivita"];

	$CF = $_SESSION["CF"];

	if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)) {
		$convalidatore = $CF;
	} else {
		$convalidatore = NULL;
	}


	// INIZIO DEI CONTROLLI

	if($inizio > $fine) {
		echo "<script>alert('La data di inizio deve essere precedente alla data di fine.');</script>";
		exit();
	}

	// Controlla se il campo è già prenotato in quell'intervallo di tempo
	$queryPrenotato = "SELECT * FROM PRENOTAZIONE WHERE Campo = ? AND ? < DataFine AND ? > DataInizio";
	$stmtPrenotato = $conn->prepare($queryPrenotato);
	$stmtPrenotato->bind_param("sss", $campo, $inizio, $fine);
	$stmtPrenotato->execute();
	$resultPrenotato = $stmtPrenotato->get_result();
	if ($resultPrenotato->num_rows > 0) {
		echo "<script>alert('Il campo è già prenotato in questo intervallo di tempo.');</script>";
		exit();
	}
	$stmtPrenotato->close();



	// FINE DEI CONTROLLI

	if($inizio > $fine) {
		echo "<script>alert('La data di inizio deve essere precedente alla data di fine.');</script>";
		exit();
	}

	// Prepara la query
	$query = "INSERT INTO PRENOTAZIONE (Prenotante, Campo, DataInizio, DataFine, Convalidatore, Attivita) VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssss", $CF, $campo, $inizio, $fine, $convalidatore, $attivita);

	// Esegui la query
	if ($stmt->execute()) {
		echo "<script>alert('Assemblea aggiunta con successo!');</script>";
		header("Location: /pages/prenotazioni/prenota.php");
		exit();
	} else {
		echo "<script>alert('Errore durante l\'aggiunta dell\'assemblea.');</script>";
	}

	// Chiudi la connessione
	$stmt->close();
}