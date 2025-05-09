<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$ruoli = $_SESSION["ruoli"];
if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
	die("Permessi insufficienti");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$anno = $_POST["anno"];
	$data = $_POST["data"];
	$oggetto = $_POST["oggetto"];
	$odg = $_POST["odg"];
	$testo = $_POST["testo"];

	$queryProtocollo = "SELECT MAX(NumProtocollo) AS max_protocollo FROM ATTO WHERE Anno = ?";
	$stmtProtocollo = $conn->prepare($queryProtocollo);
	$stmtProtocollo->bind_param("i", $anno);
	$stmtProtocollo->execute();
	$resultProtocollo = $stmtProtocollo->get_result();
	$rowProtocollo = $resultProtocollo->fetch_assoc();
	if ($rowProtocollo['max_protocollo'] !== null) {
		$protocollo = $rowProtocollo['max_protocollo'] + 1;
	} else {
		$protocollo = 1; // Se non ci sono atti per quell'anno, inizia da 1
	}

	// Prepara la query
	$queryAtto = "INSERT INTO ATTO (NumProtocollo, Anno, Data, Oggetto, ODG, Testo) VALUES (?, ?, ?, ?, ?, ?)";
	$stmtAtto = $conn->prepare($queryAtto);
	$stmtAtto->bind_param("iissss", $protocollo, $anno, $data, $oggetto, $odg, $testo);

	// Esegui la query
	if ($stmtAtto->execute()) {
		echo "<script>alert('Atto aggiunto con successo!');</script>";
		header("Location: /pages/atti/visualizza.php");
		exit();
	} else {
		echo "<script>alert('Errore durante l\'aggiunta dell\'atto.');</script>";
	}

	// Chiudi la connessione
	$stmtAtto->close();
}