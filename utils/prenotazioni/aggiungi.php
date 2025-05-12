<?php
	include "../../utils/conn.php";
	include "../../utils/verifyAndStartSession.php";

	$ruoli = $_SESSION["ruoli"];

	if(!in_array('Allenatore', $ruoli) && !in_array('Socio', $ruoli) && !in_array('Atleta', $ruoli)) {
		die("Permessi insufficienti");
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$inizio = $_POST["inizio"];
		$fine = $_POST["fine"];
		$campo = $_POST["campo"];
		$attivita = $_POST["attivita"];

		$CF = $_SESSION["CF"];

		if(in_array('Allenatore', $ruoli) || in_array('Socio', $ruoli)) {
			$convalidatore = $CF;
		} else {
			$convalidatore = NULL;
		}

		// INIZIO DEI CONTROLLI
		if($inizio > $fine) {
			die('La data di inizio deve essere precedente alla data di fine.');
			exit();
		}

		// Controlla se il campo è già prenotato in quell'intervallo di tempo
		$queryPrenotato = "SELECT * FROM PRENOTAZIONE WHERE Campo = ? AND ? < DataFine AND ? > DataInizio";
		$stmtPrenotato = $conn->prepare($queryPrenotato);
		$stmtPrenotato->bind_param("sss", $campo, $inizio, $fine);
		$stmtPrenotato->execute();
		$resultPrenotato = $stmtPrenotato->get_result();
		if ($resultPrenotato->num_rows > 0) {
			die('Il campo è già prenotato in questo intervallo di tempo.');
			exit();
		}
		$stmtPrenotato->close();
		// FINE DEI CONTROLLI

		if($inizio > $fine) {
			die('La data di inizio deve essere precedente alla data di fine.');
			exit();
		}

		$query = "INSERT INTO PRENOTAZIONE (Prenotante, Campo, DataInizio, DataFine, Convalidatore, Attivita) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssssss", $CF, $campo, $inizio, $fine, $convalidatore, $attivita);

		if ($stmt->execute()) {
			header("Location: /pages/prenotazioni/prenota.php");
			exit();
		} else {
			die('Errore durante l\'aggiunta dell\'assemblea.');
		}

		$stmt->close();
	}
	$conn->close();
?>