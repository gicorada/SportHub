<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['NumeroAssemblea']) || empty($_POST['Stato'])) {
			die("Necessario fornire i dati");
		}

        $assemblea = htmlentities($_POST['NumeroAssemblea']);
		$stato = htmlentities($_POST['Stato']);

		if($stato == "true") {
			$sql = "UPDATE PARTECIPAZIONE_ASSEMBLEA SET Confermato = TRUE WHERE (Persona = ? AND Assemblea = ?)";
		} else {
			$sql = "UPDATE PARTECIPAZIONE_ASSEMBLEA SET Confermato = FALSE WHERE (Persona = ? AND Assemblea = ?)";
		}

		$stmt = $conn->prepare($sql);

        $stmt->bind_param("ss", $_SESSION["CF"], $assemblea);
        $stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: /pages/assemblee/visualizza.php');
        } else die ("Modifica della partecipazione non riuscita");
    } else die("Devi fornire i dati tramite POST");
?>