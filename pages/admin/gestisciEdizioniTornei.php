<?php
	include '../../utils/conn.php';
	include '../../utils/verifyAndStartSession.php';

	$ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

	// Su DB modificato, adesso pk di torneo è codice
	if($_SERVER['REQUEST_METHOD'] != 'POST') header('Location: /pages/admin/gestisciTornei.php');
	if(empty($_POST['codice'])) {
		die('Necessario fornire i dati');
	}

	$codice = $_POST['codice'];

	$query = "SELECT T.Attivita, T.Sport, E_T.Anno, E_T.Sponsor, E_T.Documento FROM TORNEO T LEFT JOIN EDIZIONE_TORNEO E_T ON (T.Codice = E_T.Torneo) WHERE T.Codice = ?";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $codice);
	$stmt->execute();

	$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci le edizioni dei tornei</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50">
	<?php 
		$titleHeader = "Gestisci Edizioni Tornei";
		$activeHeader = "gestisci-edizioni-tornei";
		include "../../partials/header.php";
	?>

	<main class="max-w-7xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
		<table class="table-auto w-full border-collapse border border-gray-300">
			<thead>
				<tr class="bg-gray-100">
					<th>Codice</th>
					<th>Attività</th>
					<th>Sport</th>
					<th>Anno</th>
					<th>Sponsor</th>
					<th>Documento</th>
					<th>Azioni</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
					<tr class="hover:bg-gray-50">
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($codice) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Attivita']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Sport']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Anno'] ?? '') ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Sponsor'] ?? '') ?></td>
						<td class="border border-gray-300 px-4 py-2"><a href="//<?= htmlspecialchars($row['Documento'] ?? '') ?>"><?= htmlspecialchars($row['Documento'] ?? '') ?></a></td>

						<td class="border border-gray-300 px-4 py-2">
							<form action="../../utils/tornei/edizioni/rimuovi.php" method="POST" <?= $row['Anno'] == '' ? 'hidden' : '' ?> class="inline">
								<input type="hidden" name="codice" value="<?= htmlspecialchars($codice) ?>">
								<input type="hidden" name="anno" value="<?= htmlspecialchars($row['Anno'] ?? '') ?>">
								<button type="submit" class="text-red-500 hover:text-red-700">Elimina</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				
				<tr>
					<td colspan="3" class="text-center border border-gray-300 px-4 py-2"></td>
					<td colspan="3" class="text-center border border-gray-300 px-4 py-2">
						<button id="mostra-form" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Aggiungi nuova edizione</button>
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
				<td colspan="3" class="border border-gray-300 px-4 py-2"></td>
				<td colspan="3" class="border border-gray-300 px-4 py-2">
					<form action="../../utils/tornei/edizioni/aggiungi.php" method="POST" class="flex gap-4 justify-center">
						<input type="text" name="codice" hidden placeholder="Codice" required value="<?= htmlspecialchars($codice) ?>">
						<input type="text" name="anno" placeholder="Anno" required class="border border-gray-300 rounded px-2 py-1">
						<input type="text" name="sponsor" placeholder="Sponsor" required class="border border-gray-300 rounded px-2 py-1">
						<input type="text" name="documento" placeholder="Documento (link)" required class="border border-gray-300 rounded px-2 py-1">
						<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>