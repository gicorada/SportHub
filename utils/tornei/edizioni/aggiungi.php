<?php
    include '../../conn.php';
	include '../../verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['codice']) || empty($_POST['anno']) || empty($_POST['sponsor']) || empty($_POST['documento'])) {
			die('Necessario fornire i dati');
		}

        $codice = $_POST['codice'];
		$anno = $_POST['anno'];
		$sponsor = $_POST['sponsor'];
		$documento = $_POST['documento'];

		$sql = "INSERT INTO EDIZIONE_TORNEO(Torneo, Anno, Sponsor, Documento) VALUES (?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssss", $codice, $anno, $sponsor, $documento);
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: ../../pages/admin/gestisciEdizioniTornei.php');
        } else die ("Inserimento del campo non riuscito");
    } else die("Devi fornire i dati tramite POST");
?>