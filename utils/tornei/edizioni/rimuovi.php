<?php
    include '../../conn.php';
	include '../../verifyAndStartSession.php';

	$ruoli = $_SESSION['ruoli'];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die('Permessi insufficienti');
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['codice']) || empty($_POST['anno'])) {
			die("Necessario fornire i dati");
		}

        $codice = $_POST['codice'];
		$anno = $_POST['anno'];

		$sql = "DELETE FROM EDIZIONE_TORNEO WHERE Torneo = ? AND Anno = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $codice, $anno);
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: /pages/gestisciEdizioniTornei.php');
        } else die ('Inserimento del campo non riuscito');
    } else die('Devi fornire i dati tramite POST');
	$conn->close();
?>