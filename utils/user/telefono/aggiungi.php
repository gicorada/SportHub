<?php
    include '../../conn.php';
	include '../../verifyAndStartSession.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['telefono'])) {
			die('Necessario fornire i dati');
		}

        $telefono = $_POST['telefono'];
		$CF = $_SESSION['CF'];

		$sql = "INSERT INTO CONTATTO(NumTel, Persona) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $telefono, $CF);
		
		$stmt->execute();

        if($conn->affected_rows != 0) {
            header('Location: /pages/private/telefono.php');
        } else die ("Inserimento del numero di telefono non riuscito");
    } else die("Devi fornire i dati tramite POST");
	$conn->close();
?>