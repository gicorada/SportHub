<?php
    include '../conn.php';
	include '../verifyAndStartSession.php';

	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(empty($_POST['NumeroAssemblea']) || empty($_POST['persona'])) {
			die("Necessario fornire i dati");
		}

        $assemblea = htmlentities($_POST['NumeroAssemblea']);
		$persona = htmlentities($_POST['persona']);

		$sql = "INSERT INTO PARTECIPAZIONE_ASSEMBLEA(Persona, Assemblea) VALUES ( ?, ? )";

		$stmt = $conn->prepare($sql);

        $stmt->bind_param("ss", $persona, $assemblea);
        $stmt->execute();

		if($conn->affected_rows != 0) {
			echo "<form id='redirectForm' method='POST' action='/pages/assemblee/partecipanti.php'>
				<input type='hidden' name='assembleaPart' value='$assemblea'>
			  </form>
			  <script>
				document.getElementById('redirectForm').submit();
			  </script>";
        } else die ("Modifica della partecipazione non riuscita");
    } else die("Devi fornire i dati tramite POST");
	$conn->close();
?>