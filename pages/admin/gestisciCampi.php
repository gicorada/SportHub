<?php
	include '../../utils/conn.php';
	include '../../utils/verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

	$query = "SELECT Codice, Sport FROM CAMPO ORDER BY Codice";

	$stmt = $conn->prepare($query);
	$stmt->execute();

	$result = $stmt->get_result();

	$querySport = "SELECT Nome FROM SPORT";
	$stmtSport = $conn->prepare($querySport);
	$stmtSport->execute();
	$resultSport = $stmtSport->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci i campi</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50">
	<?php 
		$titleHeader = "Gestisci Campi";
		$activeHeader = "gestisci-campi";
		include "../../partials/header.php";
	?>
    
	<main class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">		
		<table class="table-auto w-full border-collapse border border-gray-300">
			<thead>
				<tr class="bg-gray-100">
					<th>Codice</th>
					<th>Sport</th>
					<th>Azioni</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
					<tr class="hover:bg-gray-50">
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Codice']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Sport']) ?></td>
						<td class="border border-gray-300 px-4 py-2">
							<form action="../../utils/campi/rimuovi.php" method="POST" class="inline">
								<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Codice']) ?>">
								<button type="submit" class="text-red-500 hover:text-red-700">Elimina</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				
				<tr>
					<td colspan="3" class="text-center border border-gray-300 px-4 py-2">
						<button id="mostra-form" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Aggiungi nuovo campo</button>
					</td>
				</tr>
				<tr id="form-row" style="display: none;"></tr>
			</tbody>
		</table>
	</main>

	<script>
		document.getElementById('mostra-form').addEventListener('click', function () {
			const formRow = document.getElementById('form-row');
			formRow.style.display = 'table-row';
			formRow.innerHTML = `
				<td colspan="3" class="border border-gray-300 px-4 py-2">
					<form action="../../utils/campi/aggiungi.php" method="POST" class="border border-gray-300 px-4 py-2">
						<input type="text" name="codice" placeholder="Codice" required class="border border-gray-300 rounded px-2 py-1">
						<select name="sport" id="sport" required class="mt-2 p-2 border rounded-md">
							<option value="" disabled selected>Seleziona uno sport</option>
							<?php while($sport = $resultSport->fetch_assoc()): ?>
								<option value="<?= htmlspecialchars($sport['Nome']) ?>"><?= htmlspecialchars($sport['Nome']) ?></option>
							<?php endwhile; ?>
						</select>
						<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>