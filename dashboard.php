<?php
	include './utils/verifyAndStartSession.php';
	//include './utils/conn.php';

	$ruoli = $_SESSION["ruoli"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./style/dashboard.css">
	<link rel="stylesheet" href="./style/base.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">    <title>SportHub Dashboard</title>
</head>

<body>
	<div class="dashboardButtonContainer">
		<a href="./pages/visualizzaAssemblee.php" class="dashboardButton">
			<i class="fa fa-calendar"></i>
			<h3>Visualizza assemblee</h3>	
		</a>

		<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli) || in_array('Atleta', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-futbol-o"></i>
				<h3>Prenota un campo</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-calendar"></i>
				<h3>Gestisci le prenotazioni</h3>
			</a>
		<?php endif ?>

		<a href="./pages/gestisciDati.php" class="dashboardButton">
			<i class="fa fa-user"></i>
			<h3>Gestisci i tuoi dati</h3>
		</a>

		<a href="./pages/visualizzaAtti.php" class="dashboardButton">
			<i class="fa fa-file-text"></i>
			<h3>Visualizza gli atti</h3>
		</a>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-ticket"></i>
				<h3>Gestisci i campi</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-futbol-o"></i>
				<h3>Gestisci gli sport</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-cogs"></i>
				<h3>Gestisci le attività</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-trophy"></i>
				<h3>Gestisci i tornei</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-users"></i>
				<h3>Gestisci le assemblee</h3>
			</a>
		<?php endif ?>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
			<a href="#" class="dashboardButton">
				<i class="fa fa-certificate"></i>
				<h3>Gestisci atti e nomine</h3>
			</a>
		<?php endif ?>
	</div>

</body>
</html>