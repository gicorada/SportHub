<?php
	include "../../utils/conn.php";
	include "../../utils/verifyAndStartSession.php";

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(empty($_POST['assembleaRimuovi'])) {
			die("Necessario fornire i dati");
		}

		$codice = $_POST["assembleaRimuovi"];

		$query = "DELETE FROM ASSEMBLEA WHERE Codice = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $codice);

		if ($stmt->execute()) {
			header("Location: /pages/assemblee/gestisci.php");
			exit();
		} else {
			die('Errore durante la rimozione dell\'assemblea.');
		}

		$stmt->close();
	}
	$conn->close();
?>