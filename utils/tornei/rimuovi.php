<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

	$ruoli = $_SESSION['ruoli'];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die('Permessi insufficienti');
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['codice'])) {
			die('Necessario fornire i dati');
		}

        $codice = $_POST['codice'];

		$sql = "DELETE FROM TORNEO WHERE Codice = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $codice);
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: /pages/admin/gestisciTornei.php');
        } else die ('Inserimento del campo non riuscito');
    } else die('Devi fornire i dati tramite POST');
?>