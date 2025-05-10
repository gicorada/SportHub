<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$CF = $_SESSION['CF'];

$query = "SELECT NumTel FROM CONTATTO WHERE Persona = ?;";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $CF);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci i tuoi contatti</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50">
	<?php 
		$titleHeader = "Gestisci i numeri di telefono";
		$activeHeader = "telefono";
		include "../../partials/header.php";
	?>
	
	<main class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">		
		<table class="table-auto w-full border-collapse border border-gray-300">
			<thead>
				<tr class="bg-gray-100">
					<th class="border border-gray-300 px-4 py-2 text-left">Numero di telefono</th>
					<th class="border border-gray-300 px-4 py-2 text-left">Azioni</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result as $row): ?>
					<tr class="hover:bg-gray-50">
						<td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['NumTel']) ?></td>
						<td class="border border-gray-300 px-4 py-2">
							<form action="../../utils/user/telefono/rimuovi.php" method="POST" class="inline">
								<input type="hidden" name="telefono" value="<?= htmlspecialchars($row['NumTel']) ?>">
								<button type="submit" class="text-red-500 hover:text-red-700">Elimina</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				
				<tr>
					<td colspan="2" class="text-center border border-gray-300 px-4 py-2">
						<button id="mostra-form" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Aggiungi nuovo numero</button>
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
				<td colspan="2" class="border border-gray-300 px-4 py-2">
					<form action="../../utils/user/telefono/aggiungi.php" method="POST" class="flex gap-4 justify-center">
						<input type="text" name="telefono" placeholder="Telefono" maxlength=10 required class="border border-gray-300 rounded px-2 py-1">
						<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Aggiungi</button>
					</form>
				</td>
				`;
				this.disabled = true;
			});
		</script>
	</main>
</body>
</html>