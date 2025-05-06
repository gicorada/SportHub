<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$query = "SELECT Nome FROM SPORT";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci gli sport</title>
</head>

<body>
    <h1>Gestisci gli sport</h1>
	<h2>Da qui puoi gestire gli sport praticabili nella polisportiva</h2>
    
    <table border=1>
        <thead>
            <th>Sport</th>
			<th>Azioni</th>
        </thead>
        <tbody>
			<?php foreach($result as $row): ?>
				<tr>
					<td><?= htmlspecialchars($row['Nome']) ?></td>
					<td>
						<form action="../../utils/sport/rimuovi.php" method="POST">
							<input type="hidden" name="codice" value="<?= htmlspecialchars($row['Nome']) ?>">
							<button type="submit">Elimina</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td colspan="2" style="text-align: center;">
					<button id="mostra-form">+ Aggiungi nuovo Sport</button>
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
				<td colspan="2">
					<form action="../../utils/sport/aggiungi.php" method="POST" style="display: flex; gap: 1rem; justify-content: center;">
						<input type="text" name="sport" placeholder="Sport" required>
						<button type="submit">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>