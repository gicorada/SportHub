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

</head>

<body class="bg-gray-50">
	<!-- Header -->
	<header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/logo.png" alt="Logo" class="h-20 w-auto">
                <h1 class="text-2xl font-bold">SportHub - Gestisci i tuoi contatti</h1>
            </div>
            <nav class="flex items-center gap-6">
                <a href="/dashboard.php" class="hover:text-gray-200">Dashboard</a>
                <a href="/pages/assemblee/visualizza.php" class="hover:text-gray-200">Assemblee</a>
                <a href="/pages/prenotazioni/prenota.php" class="hover:text-gray-200">Prenotazioni</a>
                <a href="/pages/private/datiPersonali.php" class="hover:text-gray-200">Dati Personali</a>
                <a href="/logout.php" class="text-red-400 hover:text-red-500">Logout</a>
            </nav>
        </div>
    </header>
	
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