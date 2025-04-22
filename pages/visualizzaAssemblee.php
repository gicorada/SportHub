<!--

QUI CI VANNO
CALENDARIO CON ASSEMBLEE

-->

<?php
include "../utils/conn.php";
include "../utils/verifyAndStartSession.php";

$query = "SELECT Codice, Data, ODG, Descrizione, CONCAT(P.Nome, ' ', P.Cognome) as Convocatore, P_A.Persona as Partecipa
			FROM ASSEMBLEA A
			JOIN PERSONA P ON (A.Convocatore = P.CF)
			LEFT JOIN PARTECIPAZIONE_ASSEMBLEA P_A ON (A.Codice = P_A.Assemblea AND ? = P_A.Persona);";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION["CF"]);
$stmt->execute();

$result = $stmt->get_result();
/*
foreach($result as $row) {
	var_dump($row);
}*/

?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@3.12.0/event-calendar.min.css">
		<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@3.12.0/event-calendar.min.js"></script>
		<link rel="stylesheet" href="../style/base.css">
	</head>
	<body>
		<main>
			<div id="ec"></div>
		</main>

		<script>
			let ec = new EventCalendar(document.getElementById('ec'), {
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
							backgroundColor: (("<?= $row["Partecipa"] ?>" != "") ? "green" : "red")
						},
					<?php endforeach; ?>
				],
				height: "80vh",
				nowIndicator: true,
			});

			function addDefaultEventDuration(date) {
				let endDate = date;
				endDate.setHours(endDate.getHours() + 1);
				return endDate;
			}
		</script>
	</body>
</html>