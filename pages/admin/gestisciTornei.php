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
    <link rel="stylesheet" href="../../style/base.css">
    <title>Gestisci i campi</title>
</head>

<body>
    <h1>Gestisci i tornei</h1>
	<h2>Da qui puoi gestire i tornei di questa polisportiva</h2>
    
    <table border=1>
        <thead>
            <th>Codice</th>
			<th>Attività</th>
			<th>Sport</th>
			<th>Edizioni</th>
			<th>Azioni</th>
        </thead>
        <tbody>
			<?php foreach($result as $row): ?>
				<tr>
					<td><?= htmlspecialchars($row['Codice']) ?></td>
					<td><?= htmlspecialchars($row['Attivita']) ?></td>
					<td><?= htmlspecialchars($row['Sport']) ?></td>
					<td>
						<form action="./gestisciEdizioniTornei.php" method="POST">
							<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Codice']) ?>">
							<button type="submit">Gestisci</button>
						</form>
					</td>
					<td>
						<form action="../../utils/tornei/rimuovi.php" method="POST">
							<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Codice']) ?>">
							<button type="submit">Elimina</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td colspan="3" style="text-align: center;">
					<button id="mostra-form">+ Aggiungi nuovo torneo</button>
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
					<form action="../../utils/tornei/aggiungi.php" method="POST" style="display: flex; gap: 1rem; justify-content: center;">
						<input type="text" name="codice" placeholder="Codice" required>
						<select name="attivita" id="attivita">
							<?php foreach($attivitaResult as $attivitaRow): ?>
								<option value="<?= $attivitaRow['Nome']?>"><?= $attivitaRow['Nome'] ?></option>
							<?php endforeach; ?>
						</select>
						<select name="sport" id="sport">
							<?php foreach($sportResult as $sportRow): ?>
								<option value="<?= $sportRow['Nome']?>"><?= $sportRow['Nome'] ?></option>
							<?php endforeach; ?>
						</select>
						<button type="submit">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>