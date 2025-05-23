<?php
    include '../../utils/conn.php';
    include '../../utils/verifyAndStartSession.php';

    $ruoli = $_SESSION['ruoli'];
	if(!in_array('Allenatore', $ruoli) && !in_array('Socio', $ruoli)) {
		die('Permessi insufficienti');
	}

    // TODO aggiunta dataFine sul DB
    $query = "SELECT ID, Prenotante, CONCAT(PE.Nome, ' ', PE.Cognome) as NomePrenotante, Campo, Sport, DataInizio, DataFine, Convalidatore, Attivita
                FROM PRENOTAZIONE PR
                JOIN PERSONA PE ON (PR.Prenotante = PE.CF)
                JOIN CAMPO C ON (PR.Campo = C.Codice);";

    $stmt = $conn->prepare($query);
    $stmt->execute();

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
    <title>Gestisci Prenotazioni</title>
	<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">
    <?php 
		$titleHeader = 'Gestisci Prenotazioni';
		$activeHeader = 'gestisci-prenotazioni';
		include '../../partials/header.php';
	?>

    <main class="max-w-7xl mx-auto p-6">
		<div class="bg-white p-6 rounded-xl shadow-md space-y-4">
			<div class="flex justify-between items-center">
				<h1 class="text-2xl font-bold">Calendario Prenotazioni</h1>

				<div class="flex items-right gap-4">
					<div>
						<label for="filter-campo" class="mr-2 font-medium">Campo</label>
						<select id="filter-campo" class="mt-2 p-2 border rounded-md">
							<option value="" disabled>Seleziona Campo<option>
							<?php while($rowCampo = $resultCampo->fetch_assoc()): ?>
								<option value="<?= $rowCampo['Codice'] ?>" <?= (($rowCampo['Codice'] == ($_GET['campo'] ?? '')) ? 'selected' : '')?>><?= htmlspecialchars($rowCampo['Codice']) ?></option>
							<?php endwhile; ?>
							<?php $resultCampo->data_seek(0); // Reset result set pointer ?>
						</select>
					</div>

					<div>
						<label for="filter-sport" class="mr-2 font-medium">Sport</label>
						<select id="filter-sport" class="mt-2 p-2 border rounded-md">
							<option value="" disabled>Seleziona Sport<option>
							<?php while($rowSport = $resultCampo->fetch_assoc()): ?>
								<option value="<?= $rowSport['Sport'] ?>" <?= (($rowSport['Sport'] == ($_GET['sport'] ?? '')) ? 'selected' : '')?>><?= htmlspecialchars($rowSport['Sport']) ?></option>
							<?php endwhile; ?>
							<?php $resultCampo->data_seek(0); // Reset result set pointer ?>
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

        <h2 class="text-3xl font-bold text-center mb-4 mt-6">Convalida la prenotazione</h2>

        <form action="../../utils/convalidaPrenotazione.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="NumeroPrenotazione" class="block text-lg font-semibold">Seleziona la prenotazione</label>
                <select name="NumeroPrenotazione" id="prenotazione" class="mt-2 p-2 border rounded-md">
                    <option value="" disabled>-- Seleziona --</option>
                    <?php foreach($result as $row): ?>
                        <option value="<?= $row['ID'] ?>"><?= htmlspecialchars($row['NomePrenotante']).' - '.htmlspecialchars($row['DataInizio']).' - '.htmlspecialchars($row['Campo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-lg font-semibold">Stato della partecipazione</label>
                <div class="flex items-center mt-2">
                    <input type="radio" name="Approvazione" value="true" id="true" required class="mr-2">
                    <label for="true" class="mr-4">Approvata</label>
                    <input type="radio" name="Approvazione" value="false" id="false" class="mr-2">
                    <label for="false">Da approvare</label>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full hover:bg-blue-700 transition">Invia l'approvazione</button>
        </form>

    </main>

    <script>
        let ec = new EventCalendar.create(document.getElementById('ec'), {
            view: 'timeGridWeek',
            events: [
                <?php foreach($result as $row): ?>
                    {
                        id: <?= $row['ID'] ?>,
                        allDay: false,
                        start: new Date("<?= $row['DataInizio'] ?>"),
                        end: new Date("<?= $row['DataFine'] ?>"),
                        title: "<?= $row['NomePrenotante'].' - '.$row['Campo'].' ('.$row['Sport'].')' ?>",
                        display: 'auto',
                        editable: false,
                        startEditable: false,
                        durationEditable: false,
                        backgroundColor: (("<?= $row['Convalidatore'] ?>" != "") ? 'green' : 'red'),
                        extendedProps: {convalidato: ("<?= $row['Convalidatore'] ?>" != "") ? true : false}
                    },
                <?php endforeach; ?>
            ],
            height: '75vh',
            nowIndicator: true,
            eventClick: (info) => selectPrenotazioneWithEvent(info.event)
        });

		document.getElementById('calendar-view').addEventListener('change', function () {
			ec.setOption('view', this.value);
		});

		document.getElementById('filter-sport').addEventListener('change', function () {
			document.location.search = 'sport=' + this.value;
		});

		document.getElementById('filter-campo').addEventListener('change', function () {
			document.location.search = 'campo=' + this.value;
		});

        function selectPrenotazioneWithEvent(event) {
            document.getElementById('prenotazione').value = event.id;

            if(event.extendedProps.convalidato) {
                document.getElementById('true').checked = true;
            } else {
                document.getElementById('false').checked = true;
            }
        }
    </script>
</body>
</html>
