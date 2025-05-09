<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$ruoli = $_SESSION["ruoli"];
if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
	die("Permessi insufficienti");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$codice = $_POST["assembleaRimuovi"];

	// Prepara la query
	$query = "DELETE FROM ASSEMBLEA WHERE Codice = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $codice);

	// Esegui la query
	if ($stmt->execute()) {
		echo "<script>alert('Assemblea rimossa con successo!');</script>";
		header("Location: /pages/assemblee/gestisci.php");
		exit();
	} else {
		echo "<script>alert('Errore durante la rimozione dell\'assemblea.');</script>";
	}

	// Chiudi la connessione
	$stmt->close();
}