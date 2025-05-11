<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

// Su DB modificato, adesso pk di torneo è codice
if($_SERVER['REQUEST_METHOD'] != 'POST') header('Location: /pages/assemblee/gestisci.php');
if(empty($_POST['assembleaPart'])) {
	die('Necessario fornire i dati');
}

$assemblea = $_POST['assembleaPart'];

$query = "SELECT P_A.Persona as CFPersona, CONCAT(P.Nome, ' ', P.Cognome) as NomePersona, P_A.Confermato
			FROM PARTECIPAZIONE_ASSEMBLEA P_A
			JOIN PERSONA P ON (P_A.Persona = P.CF)
			WHERE P_A.Assemblea = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $assemblea);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Partecipazione Assemblee</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">
	<?php 
		$titleHeader = "Visualizza Invitati Assemblee";
		$activeHeader = "invitati-assemblee";
		include "../../partials/header.php";
	?>

	<main class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">		
		<table class="table-auto w-full border-collapse border border-gray-300">
			<thead>
				<tr class="bg-gray-100">
					<th>Nome</th>
					<th>Codice Fiscale</th>
					<th>Stato conferma</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
					<tr class="hover:bg-gray-50">
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['NomePersona']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['CFPersona']) ?></td>
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Confermato']) ? "<span class='text-green-700'>Partecipa</span>" : "<span class='text-red-700'>Non partecipa</span>" ?></td>
					</tr>
				<?php endforeach; ?>

				<tr>
					<td colspan="2" class="text-center border border-gray-300 px-4 py-2">
						<button id="mostra-form" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Invita una persona</button>
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
			<td colspan="2" class="border border-gray-300 px-4 py-2">
				<form action="../../utils/assemblee/invita.php" method="POST" class="flex gap-4 justify-center">
					<input type="hidden" name="NumeroAssemblea" value="<?= htmlspecialchars($assemblea) ?>">
					<input type="text" name="persona" placeholder="CF Persona" minlength=16 maxlength=16 required class="border border-gray-300 rounded px-2 py-1">
					<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Invita</button>
				</form>
			</td>
			`;
			this.disabled = true;
		});
	</script>
</body>
</html>
