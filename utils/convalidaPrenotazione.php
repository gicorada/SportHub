<?php
    include 'conn.php';
	include 'verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Socio', $ruoli) && !in_array('Allenatore', $ruoli)) {
		die("Permessi insufficienti");
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['NumeroPrenotazione'])) {
			die("Necessario fornire i dati");
		}

        $assemblea = htmlentities($_POST['NumeroPrenotazione']);
		$stato = htmlentities($_POST['Approvazione']);

		if($stato == "true") {
			$sql = "UPDATE PRENOTAZIONE SET Convalidatore = ? WHERE ID = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ss", $_SESSION["CF"], $assemblea);
			$stmt->execute();
		} else {
			$sql = "UPDATE PRENOTAZIONE SET Convalidatore = NULL WHERE ID = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s", $assemblea);
			$stmt->execute();
		}

        if($conn->affected_rows != 0) {
            header('Location: ../pages/gestisciPrenotazioni.php');
        } else die ("Convalida della prenotazione non riuscita");
    } else die("Devi fornire i dati tramite POST");
?>