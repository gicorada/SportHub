<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

$CF = $_SESSION["CF"];
$ruoli = $_SESSION["ruoli"];
if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
	die("Permessi insufficienti");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$idAttoDaFrontend = explode("/", $_POST["atto"]);
	$protocollo = $idAttoDaFrontend[0];
	$anno = $idAttoDaFrontend[1];
	$dataInizio = $_POST["dataInizio"];
	$dataFine = $_POST["dataFine"];
	$carica = $_POST["carica"];

	// Controllo se il numero di protocollo è valido (=atto esiste)
	$queryProtocollo = "SELECT MAX(NumProtocollo) AS max_protocollo FROM ATTO WHERE Anno = ?;";
	$stmtProtocollo = $conn->prepare($queryProtocollo);
	$stmtProtocollo->bind_param("s", $anno);
	$stmtProtocollo->execute();
	$resultProtocollo = $stmtProtocollo->get_result();
	$rowProtocollo = $resultProtocollo->fetch_assoc();
	if ($rowProtocollo['max_protocollo'] < $protocollo) {
		die("Protocollo non valido");
	}

	// Controllo se l'utente può ricevere quel ruolo
	if (in_array($carica, $ruoli)) {
		$queryRuolo = "SELECT DataInizio, DataFine FROM NOMINA WHERE Persona = ? AND Carica = ?;";
		$stmtRuolo = $conn->prepare($queryRuolo);
		$stmtRuolo->bind_param("ss", $CF, $carica);
		$stmtRuolo->execute();
		$resultRuolo = $stmtRuolo->get_result();
		$rowRuolo = $resultRuolo->fetch_assoc();

		if ($rowRuolo['DataInizio'] <= $dataInizio && ($rowRuolo['DataFine'] >= $dataInizio || $rowRuolo['DataFine'] == NULL)) {
			die("L'utente possiede già questo ruolo attualmente. Assicurati che la data di inizio sia corretta.");
		}
	}

	// Controllo se il ruolo è presidente, e in questo caso se l'utente è un consigliere
	if($carica == "Presidente") {
		$queryRuolo = "SELECT DataInizio, DataFine FROM NOMINA WHERE Persona = ? AND Carica = 'Consigliere' AND (DataInizio <= ? AND (DataFine = NULL OR DataFine >= ?));";
		$stmtRuolo = $conn->prepare($queryRuolo);
		$stmtRuolo->bind_param("sss", $CF, $dataInizio, $dataInizio);
		$stmtRuolo->execute();
		$resultRuolo = $stmtRuolo->get_result();
		if ($resultRuolo->num_rows == 0) {
			die("L'utente non è un consigliere nel periodo di tempo in cui deve essere presidente. Assicurati che l'utente sia prima un consigliere.");
		}
	}

	// Prepara la query
	$queryAtto = "INSERT INTO NOMINA (Persona, Atto, AnnoAtto, Carica, DataInizio, DataFine) VALUES (?, ?, ?, ?, ?, ?)";
	$stmtAtto = $conn->prepare($queryAtto);
	$stmtAtto->bind_param("siisss", $CF, $protocollo, $anno, $carica, $dataInizio, $dataFine);

	// Esegui la query
	if ($stmtAtto->execute()) {
		echo "<script>alert('Nomina aggiunta con successo!');</script>";
		header("Location: /pages/atti/visualizza.php");
		exit();
	} else {
		echo "<script>alert('Errore durante l\'aggiunta della nomina.');</script>";
	}

	// Chiudi la connessione
	$stmtAtto->close();
}