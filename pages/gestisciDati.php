<?php
include "../utils/conn.php";
include "../utils/verifyAndStartSession.php";

$CF = $_SESSION["CF"];

$query = "SELECT Nome, Cognome, Email, TipoAtleta, TipoPersonale, SportPraticato
			FROM PERSONA P
			WHERE CF = ?;";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $CF);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<?php foreach($result as $row):?>
	
    <table border=1px>

		<thead>

			<tr>

				<th>
					Nome
				</th>

				<th>
					Cognome
				</th>

				<th>
					Email
				</th>

				<th>
					TipoAtleta
				</th>

				<th>
					Personale
				</th>

				<th>
					SportPraticato
				</th>

			</tr>

		</thead>




		 <tbody>

			 <tr>
				<td>
					<?= $row['Nome'] ?>
				</td>

			 	<td>
				 	<?= $row['Cognome'] ?>
				</td>

			 	<td>
				 	<?= $row['Email'] ?>
				</td>

			 	<td>
				 	<?= $row['TipoAtleta'] ?>
				</td>

			 	<td>
				 	<?= $row['TipoPersonale'] ?>
				</td>

			 	<td>
				 	<?= $row['SportPraticato'] ?>
				</td>
			 </tr>

		 </tbody>

	 </table>

<?php endforeach; ?>