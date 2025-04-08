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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">    <title>SportHub Dashboard</title>
</head>

<body>
	<div class="dashboardButtonContainer">
		<div class="dashboardButton">
			<i class="fa fa-calendar"></i>
			<h3>Visualizza assemblee</h3>
		</div>
	

		<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli) || in_array('Atleta', $ruoli)): ?>
			<div class="dashboardButton">
				<i class="fa fa-futbol-o"></i>
				<h3>Prenota un campo</h3>
			</div>
		<?php endif ?>

		<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
			<div class="dashboardButton">
				<i class="fa fa-calendar"></i>
				<h3>Gestisci le prenotazioni</h3>
			</div>
		<?php endif ?>

		<div class="dashboardButton">
			<i class="fa fa-user"></i>
			<h3>Gestisci i tuoi dati</h3>
		</div>

		<div class="dashboardButton">
			<i class="fa fa-file-text"></i>
			<h3>Visualizza gli atti</h3>
		</div>

		<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
		<div class="dashboardButton">
			<i class="fa fa-futbol-o"></i>
			<h3>Gestisci i campi</h3>
		</div>
			
		<?php endif ?>

	</div>


	



	<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
	Gestisci gli sport
	<?php endif ?>

	<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
	Gestisci le attività
	<?php endif ?>

	<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
	Gestisci i tornei
	<?php endif ?>

	<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) ||
			in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
	Gestisci le assemblee
	<?php endif ?>

	<?php if(in_array('Presidente', $ruoli) || in_array('Consigliere', $ruoli) || in_array('Socio', $ruoli)): ?>
	Gestisci atti e nomine
	<?php endif ?>
</body>
</html>