<?php
	include "../../utils/conn.php";
	include "../../utils/verifyAndStartSession.php";

	$ruoli = $_SESSION["ruoli"];

	if(!in_array('Allenatore', $ruoli) && !in_array('Socio', $ruoli) && !in_array('Atleta', $ruoli)) {
		die("Permessi insufficienti");
	}


	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(empty($_POST['prenotazioneRimuovi'])) {
			die("Necessario fornire i dati");
		}

		$id = $_POST["prenotazioneRimuovi"];
		$CF = $_SESSION["CF"];

		if(!in_array('Allenatore', $ruoli) && !in_array('Socio', $ruoli)) {
			$queryPrenotato = "SELECT Prenotante FROM PRENOTAZIONE WHERE ID = ?";
			$stmtPrenotato = $conn->prepare($queryPrenotato);
			$stmtPrenotato->bind_param("s", $id);
			$stmtPrenotato->execute();
			$resultPrenotato = $stmtPrenotato->get_result();
			$rowPrenotato = $resultPrenotato->fetch_assoc();
			if ($rowPrenotato['Prenotante'] != $CF) {
				die("Non puoi rimuovere una prenotazione non tua!");
			}
			$stmtPrenotato->close();
		}

		$query = "DELETE FROM PRENOTAZIONE WHERE ID = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $id);

		if ($stmt->execute()) {
			header("Location: /pages/prenotazioni/prenota.php");
		} else {
			die('Errore durante la rimozione della prenotazione.');
		}

		$stmt->close();
	}
	$conn->close();
?>