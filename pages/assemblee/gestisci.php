<?php
	include '../../utils/conn.php';
	include '../../utils/verifyAndStartSession.php';

	$ruoli = $_SESSION['ruoli'];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die('Permessi insufficienti');
	}

	// TODO aggiunta dataFine sul DB
	$query = "SELECT Codice, Data, DataFine, Descrizione FROM ASSEMBLEA A;";

	$stmt = $conn->prepare($query);
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
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">
	<?php 
		$titleHeader = 'Gestisci Assemblee';
		$activeHeader = 'gestisci-assemblee';
		include '../../partials/header.php';
	?>

	<main class="max-w-7xl mx-auto p-6">
		<div class="bg-white p-6 rounded-xl shadow-md space-y-4">
			<div class="flex justify-between items-center">
				<h1 class="text-2xl font-bold">Calendario Assemblee</h1>

				<div>
					<label for="calendar-view" class="mr-2 font-medium">Vista</label>
					<select id="calendar-view" class="mt-2 p-2 border rounded-md">
						<option value="timeGridDay">Giorno</option>
						<option value="timeGridWeek" selected>Settimana</option>
						<option value="dayGridMonth">Mese</option>
					</select>
				</div>
			</div>

			<div id="ec" class="rounded-md overflow-hidden"></div>
		</div>

		<h2 class="text-3xl font-bold text-center mb-4 mt-6">Aggiungi un'assemblea</h2>
        <p class="text-center mb-4">Seleziona data e ora dal calendario, o dal menu qui sotto</p>

		<form action="../../utils/assemblee/aggiungi.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="inizio" class="block text-lg font-semibold">Inizio</label>
				<input type="datetime-local" name="inizio" id="inizio" required class="mt-2 p-3 border rounded-md w-full">
			</div>

            <div class="mb-4">
				<label for="fine" class="block text-lg font-semibold">Fine</label>
				<input type="datetime-local" name="fine" id="fine" required class="mt-2 p-3 border rounded-md w-full">
			</div>

			<div class="mb-4">
				<label for="odg" class="block text-lg font-semibold">Ordine del Giorno</label>
				<textarea name="odg" id="odg" rows="4" class="mt-2 p-3 border rounded-md w-full" maxlength="200"></textarea>
			</div>

			<div class="mb-4">
				<label for="descrizione" class="block text-lg font-semibold">Descrizione</label>
				<textarea name="descrizione" id="descrizione" rows="4" class="mt-2 p-3 border rounded-md w-full" maxlength="100"></textarea>
			</div>

			<div>
				<input type="submit" value="Aggiungi l'assemblea" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
		</form>

		<h2 class="text-3xl font-bold text-center mb-4 mt-6">Visualizza i partecipanti dell'assemblea</h2>
        <p class="text-center mb-4">Seleziona un evento dal calendario, o dal menu qui sotto</p>

		<form action="./partecipanti.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="assembleaPart" class="block text-lg font-semibold">Assemblea</label>
                <select name="assembleaPart" id="assembleaPart" required class="mt-2 p-3 border rounded-md w-full">
                    <option value="" disabled>-- Seleziona --</option>
                    <?php foreach($result as $row): ?>
                        <option value="<?= $row['Codice'] ?>"><?= htmlspecialchars($row['Codice']) ?> - <?= htmlspecialchars($row["Descrizione"]) ?> - <?= htmlspecialchars($row['Data'])?></option>
                    <?php endforeach; ?>
                </select>
			</div>

			<div>
				<input type="submit" value="Visualizza i partecipanti" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
		</form>

        <h2 class="text-3xl font-bold text-center mb-4 mt-6">Rimuovi un'assemblea</h2>
        <p class="text-center mb-4">Seleziona un evento dal calendario, o dal menu qui sotto</p>

		<form action="/utils/assemblee/rimuovi.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
			<div class="mb-4">
				<label for="assembleaRimuovi" class="block text-lg font-semibold">Assemblea</label>
                <select name="assembleaRimuovi" id="assembleaRimuovi" required class="mt-2 p-3 border rounded-md w-full">
                    <option value="" disabled>-- Seleziona --</option>
                    <?php foreach($result as $row): ?>
                        <option value="<?= $row['Codice'] ?>"><?= htmlspecialchars($row['Codice']) ?> - <?= htmlspecialchars($row["Descrizione"]) ?> - <?= htmlspecialchars($row['Data'])?></option>
                    <?php endforeach; ?>
                </select>
			</div>

			<div>
				<input type="submit" value="Rimuovi l'assemblea" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
			</div>
		</form>
	</main>

	<script>
		let ec = new EventCalendar.create(document.getElementById('ec'), {
			view: 'timeGridWeek',
			events: [
				<?php foreach($result as $row): ?>
					{
						id: <?= $row['Codice'] ?>,
						allDay: false,
						start: new Date('<?= $row['Data'] ?>'),
						end: new Date('<?= $row['DataFine'] ?>'),
						title: "<?= $row['Descrizione'] ?>",
						display: 'auto',
						editable: false,
						startEditable: false,
						durationEditable: false,
						backgroundColor: 'darkblue',
					},
				<?php endforeach; ?>
			],
			height: "70vh",
			nowIndicator: true,
            eventClick: (info) => selectAssembleaWithEvent(info.event),
            selectable: true,
            select: (info) => selectDateTime(info),
		});

		document.getElementById('calendar-view').addEventListener('change', function () {
			ec.setOption('view', this.value);
		});

        function selectAssembleaWithEvent(event) {
			document.getElementById('assembleaRimuovi').value = event.id;
			document.getElementById('assembleaPart').value = event.id;
		}

		function selectDateTime(info) {
            console.log(info.start.toLocaleString().slice(0, 20));
            const timezoneOffset = new Date().getTimezoneOffset() * 60000; // Offset in milliseconds
            const localStart = new Date(info.start.getTime() - timezoneOffset).toISOString().slice(0, 16);
            const localEnd = new Date(info.end.getTime() - timezoneOffset).toISOString().slice(0, 16);

            document.getElementById('inizio').value = localStart;
            document.getElementById('fine').value = localEnd;
		}
	</script>
</body>
</html>
