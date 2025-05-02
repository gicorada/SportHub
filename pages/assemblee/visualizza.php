<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$query = "SELECT Codice, Data, ODG, Descrizione, CONCAT(P.Nome, ' ', P.Cognome) as Convocatore, P_A.Persona as Partecipa
			FROM ASSEMBLEA A
			JOIN PERSONA P ON (A.Convocatore = P.CF)
			LEFT JOIN PARTECIPAZIONE_ASSEMBLEA P_A ON (A.Codice = P_A.Assemblea AND ? = P_A.Persona);";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION["CF"]);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
		<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
		<link rel="stylesheet" href="../../style/base.css">
	</head>
	<body>
		<main>
			<div id="ec"></div>

			<h1>Segna la tua partecipazione</h1>
			<form action="../../utils/assemblee/partecipa.php" method="POST">
				<label for="NumeroAssemblea">Assemblea</label>
				<select name="NumeroAssemblea" id="assemblea" required>
					<option value="">-- Seleziona --</option>
					<?php foreach($result as $row): ?>
						<option value="<?= $row['Codice'] ?>"><?= $row['Codice'] ?> - <?= $row["Descrizione"] ?></option>
					<?php endforeach; ?>
				</select>
				<br>

				<label>Stato della partecipazione</label><br>
				<input type="radio" name="Stato" value="true" id="true" required><label for="true">Partecipo</label><br>
				<input type="radio" name="Stato" value="false" id="false"><label for="false">Non partecipo</label><br>

				<input type="submit" value="Invia lo stato">
			</form>
		</main>

		<script>
			let ec = new EventCalendar.create(document.getElementById('ec'), {
				view: 'timeGridWeek',
				events: [
					<?php foreach($result as $row): ?>
						{
							id: <?= $row["Codice"] ?>,
							allDay: false,
							start: new Date("<?= $row["Data"] ?>"),
							end: addDefaultEventDuration(new Date("<?= $row["Data"] ?>")),
							title: "<?= $row["Descrizione"] ?>",
							display: "auto",
							editable: false,
							startEditable: false,
							durationEditable: false,
							backgroundColor: (("<?= $row["Partecipa"] ?>" != "") ? "green" : "red"),
							extendedProps: {partecipa: ("<?= $row["Partecipa"] ?>" != "") ? true : false}
						},
					<?php endforeach; ?>
				],
				height: "80vh",
				nowIndicator: true,
				eventClick: (info) => selectAssembleaWithEvent(info.event)
			});

			function addDefaultEventDuration(date) {
				let endDate = date;
				endDate.setHours(endDate.getHours() + 1);
				return endDate;
			}

			function selectAssembleaWithEvent(event) {
				document.getElementById("assemblea").value = event.id;

				if(event.extendedProps.partecipa) {
					document.getElementById("true").checked = true;
				} else {
					document.getElementById("false").checked = true;
				}
			}
		</script>
	</body>
</html>