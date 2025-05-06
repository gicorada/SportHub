<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$CF = $_SESSION['CF'];
$ruoli = $_SESSION['ruoli'];

$query = "SELECT Nome, Cognome, Email, TipoAtleta, TipoPersonale, SportPraticato
			FROM PERSONA P
			WHERE CF = ?;";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $CF);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$nome = htmlspecialchars($row['Nome']);
$cognome = htmlspecialchars($row['Cognome']);
$email = htmlspecialchars($row['Email']);

if(in_array('Altro personale', $ruoli)) {
	$tipopersonale = htmlspecialchars($row['TipoPersonale']);
}

if(in_array('Atleta', $ruoli)) {
	$sportQuery = "SELECT Nome FROM SPORT";
	$sportResult = $conn->query($sportQuery);
	$tipoatleta = htmlspecialchars($row['TipoAtleta']);
	$sportpraticato = htmlspecialchars($row['SportPraticato']);
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci i tuoi dati</title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
	<!-- Header -->
	<header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/logo.png" alt="Logo" class="h-20 w-auto">
                <h1 class="text-2xl font-bold">SportHub - Gestisci i tuoi dati</h1>
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
		<h2 class="text-xl font-semibold mb-4">Modifica un campo per aggiornare i tuoi dati</h2>

		<form action="/utils/user/updateProfilo.php" method="POST" class="space-y-4">
			<div>
				<label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
				<input type="text" name="nome" id="nome" placeholder="Nome" value="<?= $nome ?>" required
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
			</div>
			
			<div>
				<label for="cognome" class="block text-sm font-medium text-gray-700">Cognome</label>
				<input type="text" name="cognome" id="cognome" placeholder="Cognome" value="<?= $cognome ?>" required
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
			</div>
			
			<div>
				<label for="email" class="block text-sm font-medium text-gray-700">Email</label>
				<input type="email" name="email" id="email" value="<?= $email ?>" disabled required
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
			</div>
			
			<?php if(in_array('Altro personale', $ruoli)): ?>
				<div>
					<label for="tipopersonale" class="block text-sm font-medium text-gray-700">Tipo Personale</label>
					<input type="text" name="tipopersonale" id="tipopersonale" value="<?= $tipopersonale ?>"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
				</div>
			<?php endif; ?>
				
			<?php if(in_array('Atleta', $ruoli)): ?>
				<div>
					<label for="tipoatleta" class="block text-sm font-medium text-gray-700">Tipo Atleta</label>
					<select name="tipoatleta" id="tipoatleta"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
						<option value="Agonistico" <?= (($tipoatleta == 'Agonistico') ? 'selected' : '')?>>Agonistico</option>
						<option value="Amatoriale" <?= (($tipoatleta == 'Amatoriale') ? 'selected' : '')?>>Amatoriale</option>
					</select>
				</div>
				
				<div>
					<label for="sportpraticato" class="block text-sm font-medium text-gray-700">Sport Praticato</label>
					<select name="sportpraticato" id="sportpraticato"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
						<?php foreach($sportResult as $sportRow): ?>
							<option value="<?= $sportRow['Nome']?>" <?= (($sportpraticato == $sportRow['Nome']) ? 'selected' : '')?>><?= $sportRow['Nome'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			
			<div>
				<input type="submit" value="Aggiorna dati"
					class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
			</div>
		</form>
		
		<div class="mt-6">
			<a href="./telefono.php">
				<button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
					Gestisci i tuoi numeri di telefono
				</button>
			</a>
		</div>
	</main>
</body>
</html>