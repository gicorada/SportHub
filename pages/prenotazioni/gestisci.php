<!--

QUI CI VANNO
APPROVAZIONE DELLE PRENOTAZIONI (FORM CON SELECT?)

-->

<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$query = "SELECT ID, Prenotante, CONCAT(PE.Nome, ' ', PE.Cognome) as NomePrenotante, Campo, Sport, DataInizio, Convalidatore, Attivita
			FROM PRENOTAZIONE PR
			JOIN PERSONA PE ON (PR.Prenotante = PE.CF)
			JOIN CAMPO C ON (PR.Campo = C.Codice)
			;";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
		<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
		<link rel="stylesheet" href="../../style/base.css">
        <title>Gestisci prenotazioni</title>
    </head>
    <body>
		<div id="ec"></div>
        
		<h1>Segna la tua partecipazione</h1>
		<form action="../../utils/convalidaPrenotazione.php" method="POST">
			<label for="NumeroPrenotazione">Assemblea</label>
			<select name="NumeroPrenotazione" id="prenotazione" required>
				<option value="">-- Seleziona --</option>
				<?php foreach($result as $row): ?>
					<option value="<?= $row['ID'] ?>"><?= $row["NomePrenotante"]." - ".$row["DataInizio"]." - ".$row["Campo"] ?></option>
				<?php endforeach; ?>
			</select>
			<br>

			<label>Stato della partecipazione</label><br>
			<input type="radio" name="Approvazione" value="true" id="true" required><label for="true">Approvata</label><br>
			<input type="radio" name="Approvazione" value="false" id="false"><label for="false">Da approvare</label><br>

			<input type="submit" value="Invia l'approvazione">
		</form>

		<script>
			let ec = new EventCalendar.create(document.getElementById('ec'), {
				view: 'timeGridWeek',
				events: [
					<?php foreach($result as $row): ?>
						{
							id: <?= $row["ID"] ?>,
							allDay: false,
							start: new Date("<?= $row["DataInizio"] ?>"),
							end: addDefaultEventDuration(new Date("<?= $row["DataInizio"] ?>")),
							title: "<?= $row["NomePrenotante"]." - ".$row["Campo"]." (".$row["Sport"].")" ?>",
							display: "auto",
							editable: false,
							startEditable: false,
							durationEditable: false,
							backgroundColor: (("<?= $row["Convalidatore"] ?>" != "") ? "green" : "red"),
							extendedProps: {convalidato: ("<?= $row["Convalidatore"] ?>" != "") ? true : false}
						},
					<?php endforeach; ?>
				],
				height: "75vh",
				nowIndicator: true,
				eventClick: (info) => selectPrenotazioneWithEvent(info.event)
			});


			function addDefaultEventDuration(date) {
				let endDate = date;
				endDate.setHours(endDate.getHours() + 1);
				return endDate;
			}

			function selectPrenotazioneWithEvent(event) {
				document.getElementById("prenotazione").value = event.id;

				if(event.extendedProps.convalidato) {
					document.getElementById("true").checked = true;
				} else {
					document.getElementById("false").checked = true;
				}
			}
		</script>
    </body>
</html>