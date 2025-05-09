<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

// Su DB modificato, adesso pk di torneo è codice
$query = "SELECT Codice, Attivita, Sport FROM TORNEO T";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

$attivitaQuery = "SELECT Nome FROM ATTIVITA";
$attivitaResult = $conn->query($attivitaQuery);

$sportQuery = "SELECT Nome FROM SPORT";
$sportResult = $conn->query($sportQuery);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci i tornei</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-50">
	<?php 
		$titleHeader = "Gestisci Tornei";
		$activeHeader = "gestisci-tornei";
		include "../../partials/header.php";
	?>

	<main class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">		
		<table class="table-auto w-full border-collapse border border-gray-300">
			<thead>
				<tr class="bg-gray-100">
					<th>Codice</th>
					<th>Attività</th>
					<th>Sport</th>
					<th>Edizioni</th>
					<th>Azioni</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
					<tr class="hover:bg-gray-50">
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Codice']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Attivita']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Sport']) ?></td>
						<td class="border border-gray-300 px-4 py-2">
							<form action="./gestisciEdizioniTornei.php" method="POST" class="inline">
								<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Codice']) ?>">
								<button type="submit" class="text-blue-500 hover:text-blue-700">Gestisci</button>
							</form>
						</td>
						<td class="border border-gray-300 px-4 py-2">
							<form action="../../utils/tornei/rimuovi.php" method="POST" class="inline">
								<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Codice']) ?>">
								<button type="submit" class="text-red-500 hover:text-red-700">Elimina</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				
				<tr>
					<td colspan="5" class="text-center border border-gray-300 px-4 py-2">
						<button id="mostra-form" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Aggiungi nuovo torneo</button>
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
				<td colspan="5" class="border border-gray-300 px-4 py-2">
					<form action="../../utils/tornei/aggiungi.php" method="POST" class="flex gap-4 justify-center">
						<input type="text" name="codice" placeholder="Codice" required class="border border-gray-300 rounded px-2 py-1">
						<select name="attivita" id="attivita" class="mt-2 p-2 border rounded-md">
							<option value="" disabled selected>Seleziona attività</option>
							<?php foreach($attivitaResult as $attivitaRow): ?>
								<option value="<?= $attivitaRow['Nome']?>"><?= $attivitaRow['Nome'] ?></option>
							<?php endforeach; ?>
						</select>
						<select name="sport" id="sport" class="mt-2 p-2 border rounded-md">
							<option value="" disabled selected>Seleziona sport</option>
							<?php foreach($sportResult as $sportRow): ?>
								<option value="<?= $sportRow['Nome']?>"><?= $sportRow['Nome'] ?></option>
							<?php endforeach; ?>
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