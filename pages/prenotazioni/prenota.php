<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

// TODO aggiunta dataFine sul DB
// TODO aggiunta FK convalidatore sul DB
$query = "SELECT ID, Prenotante, CONCAT(PE.Nome, ' ', PE.Cognome) as NomePrenotante, Campo, Sport, DataInizio, DataFine, Convalidatore, Attivita
			FROM PRENOTAZIONE PR
			JOIN PERSONA PE ON (PR.Prenotante = PE.CF)
			JOIN CAMPO C ON (PR.Campo = C.Codice)";

if(isset($_GET["sport"]) && $_GET["sport"] != "") {
	$sportDaFiltrare = $_GET["sport"];
	$query .= " WHERE Sport = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $sportDaFiltrare);
	$stmt->execute();
} else {
	$stmt = $conn->prepare($query);
	$stmt->execute();
}
$result = $stmt->get_result();

$queryCampo = "SELECT Codice, Sport FROM CAMPO";
$stmtCampo = $conn->prepare($queryCampo);
$stmtCampo->execute();
$resultCampo = $stmtCampo->get_result();

$queryAttivita = "SELECT Nome FROM ATTIVITA";
$stmtAttivita = $conn->prepare($queryAttivita);
$stmtAttivita->execute();
$resultAttivita = $stmtAttivita->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Prenotazione Campi</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
	<script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
</head>
<body class="bg-gray-50">
	<?php 
		$titleHeader = "Prenota Campo";
		$activeHeader = "prenota";
		include "../../partials/header.php";
	?>
	<main class="max-w-7xl mx-auto p-6">
		<div class="bg-white p-6 rounded-xl shadow-md space-y-4">
			<div class="flex justify-between items-center">
				<h1 class="text-2xl font-bold">Calendario Prenotazioni</h1>

				<div class="flex items-right gap-4">
					<div>
						<label for="filter-sport" class="mr-2 font-medium">Filtra</label>
						<select id="filter-sport" class="mt-2 p-2 border rounded-md">
							<option value="" disabled>Seleziona Sport<option>
							<?php while($rowSport = $resultCampo->fetch_assoc()): ?>
								<option value="<?= $rowSport['Sport'] ?>" <?= (($rowSport['Sport'] == ($_GET['sport'] ?? '')) ? 'selected' : '')?>><?= $rowSport['Sport'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
	
					<div>
						<label for="calendar-view" class="mr-2 font-medium">Vista</label>
						<select id="calendar-view" class="mt-2 p-2 border rounded-md">
							<option value="timeGridDay">Giorno</option>
							<option value="timeGridWeek" selected>Settimana</option>
							<option value="dayGridMonth">Mese</option>
						</select>
					</div>
				</div>
			</div>

			<div id="ec" class="rounded-md overflow-hidden"></div>
		</div>

		<h2 class="text-3xl font-bold text-center mb-4 mt-6">Prenota un campo</h2>
        <p class="text-center mb-4">Seleziona data e ora dal calendario, o dal menu qui sotto</p>

		<form action="/utils/prenotazioni/aggiungi.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="inizio" class="block text-lg font-semibold">Inizio</label>
				<input type="datetime-local" name="inizio" id="inizio" required class="mt-2 p-3 border rounded-md w-full">
			</div>

            <div class="mb-4">
				<label for="fine" class="block text-lg font-semibold">Fine</label>
				<input type="datetime-local" name="fine" id="fine" required class="mt-2 p-3 border rounded-md w-full">
			</div>

			<div class="mb-4">
				<label for="campo" class="block text-lg font-semibold">Campo</label>
				<select name="campo" id="campo" required class="mt-2 p-3 border rounded-md w-full">
					<option value="" disabled>-- Seleziona --</option>
					<?php while($rowCampo = $resultCampo->fetch_assoc()): ?>
						<option value="<?= $rowCampo['Codice'] ?>"><?= $rowCampo['Codice'] ?> - <?= $rowCampo['Sport'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>

			<div class="mb-4">
				<label for="campo" class="block text-lg font-semibold">Attività</label>
				<select name="attivita" id="attivita" required class="mt-2 p-3 border rounded-md w-full">
					<option value="">-- Seleziona --</option>
					<?php while($rowAttivita = $resultAttivita->fetch_assoc()): ?>
						<option value="<?= $rowAttivita['Nome'] ?>"><?= $rowAttivita['Nome'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>

			<div>
				<input type="submit" value="Invia la prenotazione" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
		</form>

        <h2 class="text-3xl font-bold text-center mb-4 mt-6">Rimuovi una tua prenotazione</h2>
        <p class="text-center mb-4">Seleziona un evento dal calendario, o dal menu qui sotto</p>

		<form action="../../utils/prenotazioni/rimuovi.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="prenotazioneRimuovi" class="block text-lg font-semibold">Inizio</label>
                <select name="prenotazioneRimuovi" id="prenotazioneRimuovi" required class="mt-2 p-3 border rounded-md w-full">
                    <option value="">-- Seleziona --</option>
                    <?php foreach($result as $row): ?>
                        <option value="<?= $row['ID'] ?>"><?= $row['ID'] ?> - <?= $row["DataInizio"] ?></option>
                    <?php endforeach; ?>
                </select>
			</div>

			<div>
				<input type="submit" value="Cancella la prenotazione" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
		</form>
	</main>

	<script>
		let ec = new EventCalendar.create(document.getElementById('ec'), {
			view: 'timeGridWeek',
			events: [
				<?php foreach($result as $row): ?>
					{
                        id: <?= $row["ID"] ?>,
                        allDay: false,
                        start: new Date("<?= $row["DataInizio"] ?>"),
                        end: new Date("<?= $row["DataFine"] ?>"),
                        title: "<?= $row["Campo"]." (".$row["Sport"].")" ?>",
                        display: "auto",
                        editable: false,
                        startEditable: false,
                        durationEditable: false,
                        backgroundColor: "darkblue",
                    },
				<?php endforeach; ?>
			],
			height: "70vh",
			nowIndicator: true,
            eventClick: (info) => selectPrenotazioneWithEvent(info.event),
            selectable: true,
            select: (info) => selectDateTime(info),
		});

		document.getElementById("calendar-view").addEventListener("change", function () {
			ec.setOption("view", this.value);
		});

		document.getElementById("filter-sport").addEventListener("change", function () {
			document.location.search = "sport=" + this.value;
		});

        function selectPrenotazioneWithEvent(event) {
			document.getElementById("prenotazioneRimuovi").value = event.id;
		}

		function selectDateTime(info) {
            console.log(info.start.toLocaleString().slice(0, 20));
            const timezoneOffset = new Date().getTimezoneOffset() * 60000; // Offset in milliseconds
            const localStart = new Date(info.start.getTime() - timezoneOffset).toISOString().slice(0, 16);
            const localEnd = new Date(info.end.getTime() - timezoneOffset).toISOString().slice(0, 16);

            document.getElementById("inizio").value = localStart;
            document.getElementById("fine").value = localEnd;
		}
	</script>
</body>
</html>
