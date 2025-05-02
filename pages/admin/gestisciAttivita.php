<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$query = "SELECT Nome FROM ATTIVITA";

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
    <link rel="stylesheet" href="../../style/base.css">
    <title>Gestisci gli sport</title>
</head>

<body>
    <h1>Gestisci le attività</h1>
	<h2>Da qui puoi gestire gli le attività praticabili nella polisportiva</h2>
    
    <table border=1>
        <thead>
            <th>Attività</th>
			<th>Azioni</th>
        </thead>
        <tbody>
			<?php foreach($result as $row): ?>
				<tr>
					<td><?= htmlspecialchars($row['Nome']) ?></td>
					<td>
						<form action="../../utils/attivita/rimuovi.php" method="POST">
							<input type="hidden" name="nome" value="<?= htmlspecialchars($row['Nome']) ?>">
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
					<form action="../../utils/attivita/aggiungi.php" method="POST" style="display: flex; gap: 1rem; justify-content: center;">
						<input type="text" name="nome" placeholder="Sport" required>
						<button type="submit">Aggiungi</button>
					</form>
				</td>
			`;
			this.disabled = true;
		});
	</script>

</body>
</html>