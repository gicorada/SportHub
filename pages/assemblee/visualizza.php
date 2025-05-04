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
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Partecipazione Assemblee</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
</head>
<body class="bg-gray-50">
	<!-- Header -->
    <header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/path/to/logo.png" alt="Logo" class="h-8 w-auto">
                <h1 class="text-2xl font-bold">SportHub - Visualizza le Assemblee</h1>
            </div>
            <nav class="flex items-center gap-6">
                <a href="../../dashboard.php" class="hover:text-gray-200">Dashboard</a>
                <a href="#" class="hover:text-gray-200">Assemblee</a>
                <a href="../prenotazioni/prenota.php" class="hover:text-gray-200">Prenotazioni</a>
                <a href="../private/datiPersonali.php" class="hover:text-gray-200">Dati Personali</a>
                <a href="../../logout.php" class="text-red-400 hover:text-red-500">Logout</a>
            </nav>
        </div>
    </header>

	<main class="max-w-7xl mx-auto p-6">
		<div class="bg-white p-6 rounded-xl shadow-md space-y-4">
			<div class="flex justify-between items-center">
				<h1 class="text-2xl font-bold">Calendario Assemblee</h1>

				<div>
					<label for="calendar-view" class="mr-2 font-medium">Vista</label>
					<select id="calendar-view" class="border-gray-300 rounded-md shadow-sm">
						<option value="timeGridDay">Giorno</option>
						<option value="timeGridWeek" selected>Settimana</option>
						<option value="dayGridMonth">Mese</option>
					</select>
				</div>
			</div>

			<div id="ec" class="rounded-md overflow-hidden"></div>
		</div>

		<h2 class="text-3xl font-bold text-center mb-4 mt-6">Segna la tua partecipazione</h2>

		<form action="../../utils/assemblee/partecipa.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="assemblea" class="block text-lg font-semibold">Assemblea</label>
				<select name="NumeroAssemblea" id="assemblea" required class="mt-2 p-3 border rounded-md w-full">
					<option value="">-- Seleziona --</option>
					<?php foreach($result as $row): ?>
						<option value="<?= $row['Codice'] ?>"><?= $row['Codice'] ?> - <?= $row["Descrizione"] ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div>
				<p class="font-medium mb-2">Stato della partecipazione</p>
				<div class="flex items-center space-x-4">
					<label class="inline-flex items-center">
						<input type="radio" name="Stato" value="true" id="true" required class="form-radio text-indigo-600">
						<span class="ml-2">Partecipo</span>
					</label>
					<label class="inline-flex items-center">
						<input type="radio" name="Stato" value="false" id="false" class="form-radio text-indigo-600">
						<span class="ml-2">Non partecipo</span>
					</label>
				</div>
			</div>

			<div>
				<input type="submit" value="Invia lo stato" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
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
			height: "70vh",
			nowIndicator: true,
			eventClick: (info) => selectAssembleaWithEvent(info.event)
		});

		document.getElementById("calendar-view").addEventListener("change", function () {
			ec.setOption("view", this.value);
		});


		function addDefaultEventDuration(date) {
			let endDate = new Date(date);
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
