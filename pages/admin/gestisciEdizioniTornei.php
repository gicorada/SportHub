<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

// Su DB modificato, adesso pk di torneo è codice


if($_SERVER['REQUEST_METHOD'] != 'POST') die("Devi fornire i dati tramite POST");
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
    <title>Gestisci i campi</title>
</head>

<body>
    <h1>Gestisci le edizioni dei tornei</h1>
	<h2>Da qui puoi gestire le edizioni dei tornei di questa polisportiva</h2>
    
    <table border=1>
        <thead>
            <th>Codice</th>
			<th>Attività</th>
			<th>Sport</th>
			<th>Anno</th>
			<th>Sponsor</th>
			<th>Documento</th>
			<th>Azioni</th>
        </thead>
        <tbody>
			<?php foreach($result as $row): ?>
				<tr>
					<td><?= htmlspecialchars($codice) ?></td>
					<td><?= htmlspecialchars($row['Attivita']) ?></td>
					<td><?= htmlspecialchars($row['Sport']) ?></td>
					<td><?= htmlspecialchars($row['Anno'] ?? '') ?></td>
					<td><?= htmlspecialchars($row['Sponsor'] ?? '') ?></td>
					<td><a href="//<?= htmlspecialchars($row['Documento'] ?? '') ?>"><?= htmlspecialchars($row['Documento'] ?? '') ?></a></td>

					<td>
						<form action="../../utils/tornei/edizioni/rimuovi.php" method="POST" <?= $row['Anno'] == '' ? 'hidden' : '' ?>>
							<input type="hidden" name="codice" value="<?= htmlspecialchars($codice) ?>">
							<input type="hidden" name="anno" value="<?= htmlspecialchars($row['Anno'] ?? '') ?>">
							<button type="submit">Elimina</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td colspan="3"></td>
				<td colspan="3" style="text-align: center;">
					<button id="mostra-form">+ Aggiungi nuova edizione</button>
				</td>
			</tr>
			<tr id="form-row" style="display: none;"></tr>
        </tbody>
    </table>

	<script>
		document.getElementById('mostra-form').addEventListener('click', function () {
			const formRow = document.getElementById('form-row');
			formRow.style.display = 'table-row';
			formRow.innerHTML = `
				<td colspan="3">
				</td>
				<td colspan="3">
					<form action="../../utils/tornei/edizioni/aggiungi.php" method="POST" style="display: flex; gap: 1rem; justify-content: center;">
						<input type="text" name="codice" hidden placeholder="Codice" required value="<?= htmlspecialchars($codice) ?>">
						<input type="text" name="anno" placeholder="Anno" required>
						<input type="text" name="sponsor" placeholder="Sponsor" required>
						<input type="text" name="documento" placeholder="Documento (link)" required>
						<button type="submit">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>