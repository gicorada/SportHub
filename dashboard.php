<?php
	include './utils/verifyAndStartSession.php';
	$ruoli = $_SESSION["ruoli"];
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SportHub Dashboard</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">
	<?php 
		$titleHeader = "Dashboard";
		$activeHeader = "dashboard";
		include "./partials/header.php";
	?>

	<main class="max-w-7xl mx-auto px-6 py-10">
		<h1 class="text-3xl font-bold mb-8">Dashboard</h1>

		<div class="grid gap-6 grid-cols-1 sm:grid-cols-4 md:grid-cols-3 xl:grid-cols-6">
			<a href="./pages/assemblee/visualizza.php" class="dashboard-card">
				<i class="fa fa-calendar text-indigo-600 text-4xl"></i>
				<span class="text-lg font-medium mt-4 text-center">Visualizza assemblee</span>
			</a>

			<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli) || in_array('Atleta', $ruoli)): ?>
				<a href="./pages/prenotazioni/prenota.php" class="dashboard-card">
					<i class="fa fa-futbol-o text-green-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Prenota un campo</span>
				</a>
			<?php endif ?>

			<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
				<a href="./pages/prenotazioni/gestisci.php" class="dashboard-card">
					<i class="fa fa-calendar text-yellow-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci le prenotazioni</span>
				</a>
			<?php endif ?>

			<a href="./pages/private/datiPersonali.php" class="dashboard-card">
				<i class="fa fa-user text-blue-600 text-4xl"></i>
				<span class="text-lg font-medium mt-4 text-center">Gestisci i tuoi dati</span>
			</a>

			<a href="./pages/atti/visualizza.php" class="dashboard-card">
				<i class="fa fa-file-text text-purple-600 text-4xl"></i>
				<span class="text-lg font-medium mt-4 text-center">Visualizza gli atti</span>
			</a>

			<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
				<a href="./pages/admin/gestisciCampi.php" class="dashboard-card">
					<i class="fa fa-ticket text-red-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci i campi</span>
				</a>

				<a href="./pages/admin/gestisciSport.php" class="dashboard-card">
					<i class="fa fa-futbol-o text-emerald-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci gli sport</span>
				</a>

				<a href="./pages/admin/gestisciAttivita.php" class="dashboard-card">
					<i class="fa fa-cogs text-gray-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci le attività</span>
				</a>

				<a href="./pages/admin/gestisciTornei.php" class="dashboard-card">
					<i class="fa fa-trophy text-amber-600 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci i tornei</span>
				</a>

				<a href="./pages/atti/gestisci.php" class="dashboard-card">
					<i class="fa fa-certificate text-indigo-500 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci atti e nomine</span>
				</a>
			<?php endif ?>

			<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
				<a href="./pages/assemblee/gestisci.php" class="dashboard-card">
					<i class="fa fa-users text-pink-500 text-4xl"></i>
					<span class="text-lg font-medium mt-4 text-center">Gestisci le assemblee</span>
				</a>
			<?php endif ?>
		</div>
	</main>

	<style>
		.dashboard-card {
			@apply flex flex-col items-center justify-center bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition hover:-translate-y-1 border border-gray-200 hover:border-indigo-300;
			text-decoration: none;
		}
	</style>

</body>
</html>
