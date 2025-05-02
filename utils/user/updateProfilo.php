<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

	$ruoli = $_SESSION['ruoli'];
	$CF = $_SESSION['CF'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['nome']) || empty($_POST['cognome'])) {
			die("Necessario fornire i dati");
		}

		$nome = $conn->real_escape_string($_POST['nome']);
		$cognome = $conn->real_escape_string($_POST['cognome']);
		
		$sql = "UPDATE PERSONA SET Nome = '$nome', Cognome = '$cognome'";
		
		if(in_array('Altro personale', $ruoli)) {
			if(empty($_POST['tipopersonale'])) {
				die('Necessario fornire i dati');
			}

			$tipopersonale = $conn->real_escape_string($_POST['tipopersonale']);
			$sql .= ", TipoPersonale = '$tipopersonale'";
		}
		
		if(in_array('Atleta', $ruoli)) {
			if(empty($_POST['tipoatleta']) || empty($_POST['sportpraticato'])) {
				die('Necessario fornire i dati');
			}

			$sportQuery = "SELECT Nome FROM SPORT";
			$sportResult = $conn->query($sportQuery);

			$tipoatleta = $conn->real_escape_string($_POST['tipoatleta']);
			$sportpraticato = $conn->real_escape_string($_POST['sportpraticato']);
			
			if($tipoatleta != 'Agonistico' && $tipoatleta != 'Amatoriale')
				die('Tipo atleta non valido');

			$sportFound = false;
			foreach($sportResult as $sportRow) {
				if($sportRow['Nome'] == $sportpraticato) {
					$sportFound = true;
					break;
				}
			}
			if (!$sportFound) {
				die('Sport non presente nella polisportiva');
			}

			$sql .= ", TipoAtleta = '$tipoatleta', SportPraticato = '$sportpraticato'";
		}
		
		
		$sql .= " WHERE CF = '$CF';";

		
		$result = $conn->query($sql);
		var_dump($conn->affected_rows);

        if($conn->errno == 1062 || $conn->errno == 0) {
            header('Location: ../pages/gestisciDati.php');
        } else die ('Modifica dei dati personali non riuscita');
    } else die('Devi fornire i dati tramite POST');
?>