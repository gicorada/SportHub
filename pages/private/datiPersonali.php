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
    <link rel="stylesheet" href="../../style/signupStyle.css">
    <link rel="stylesheet" href="../../style/base.css">
    <title>Gestisci i tuoi dati</title>
</head>

<body>
    <h1>Il tuo profilo</h1>
	<h2>Modifica un campo per aggiornare i tuoi dati</h2>
    <form action="../../utils/updateProfilo.php" method="POST">
		<label for="nome">Nome</label>
		<input type="text" name="nome" id="nome" placeholder="Nome" value="<?= $nome ?>" required>

        <label for="cognome">Cognome</label>
		<input type="text" name="cognome" id="cognome" placeholder="Cognome" value="<?= $cognome ?>" required>

		<label for="email">Email</label>
		<input type="email" name="email" id="email" value="<?= $email ?>" disabled required>

		<?php if(in_array('Altro personale', $ruoli)): ?>
			<label for="tipopersonale">Tipo Atleta</label>
			<input type="text" name="tipopersonale" id="tipopersonale" value="<?= $tipopersonale ?>">
		<?php endif; ?>

		<?php if(in_array('Atleta', $ruoli)): ?>
			<label for="tipoatleta">Tipo Atleta</label>
			<select name="tipoatleta" id="tipoatleta">
				<option value="Agonistico" <?= (($tipoatleta == 'Agonistico') ? 'selected' : '')?>>Agonistico</option>
				<option value="Amatoriale" <?= (($tipoatleta == 'Amatoriale') ? 'selected' : '')?>>Amatoriale</option>
			</select>

			<label for="sportpraticato">Sport Praticato</label>
			<select name="sportpraticato" id="sportpraticato">
				<?php foreach($sportResult as $sportRow): ?>
					<option value="<?= $sportRow['Nome']?>" <?= (($sportpraticato == $sportRow['Nome']) ? 'selected' : '')?>><?= $sportRow['Nome'] ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>

		<input type="submit" value="Aggiorna dati">
    </form> 
</body>
</html>