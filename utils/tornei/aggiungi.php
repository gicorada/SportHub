<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['codice']) || empty($_POST['attivita']) || empty($_POST['sport'])) {
			die('Necessario fornire i dati');
		}

        $codice = $_POST['codice'];
		$attivita = $_POST['attivita'];
		$sport = $_POST['sport'];

		$sql = "INSERT INTO TORNEO(Codice, Attivita, Sport) VALUES (?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("sss", $codice, $attivita, $sport);
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: ../../pages/admin/gestisciTornei.php');
        } else die ("Inserimento del campo non riuscito");
    } else die("Devi fornire i dati tramite POST");
?>