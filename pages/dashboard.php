<?php
	include '../utils/verifyAndStartSession.php';
	include '../utils/conn.php';

	$ruoli = $_SESSION["ruoli"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/signupStyle.css">
    <title>SportHub Dashboard</title>
</head>

<body>
    <?php if(in_array('Presidente', $ruoli)): ?>
		Questo lo può vedere solo il presidente
	<?php endif?>

	<?php if(in_array('Socio', $ruoli)): ?>
		Questo lo può vedere solo un socio
	<?php endif ?>

	<?php if(in_array('Socio', $ruoli) || in_array('Allenatore', $ruoli)): ?>
		Questo lo possono vedere soci e allenatori
	<?php endif ?>
</body>
</html>