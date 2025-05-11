<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['codice']) || empty($_POST["sport"])) {
			die("Necessario fornire i dati");
		}

        $codice = $_POST['codice'];
		$sport = $_POST['sport'];

		$sportQuery = "SELECT Nome FROM SPORT";
		$sportResult = $conn->query($sportQuery);

		$sportFound = false;
		foreach($sportResult as $sportRow) {
			if($sportRow['Nome'] == $sport) {
				$sportFound = true;
				break;
			}
		}
		if (!$sportFound) {
			die('Sport non presente nella polisportiva');
		}

		$sql = "INSERT INTO CAMPO(Codice, Sport) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $codice, $sport);
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: /pages/admin/gestisciCampi.php');
        } else die ("Inserimento del campo non riuscito");
    } else die("Devi fornire i dati tramite POST");
	$conn->close();
?>