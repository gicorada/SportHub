<?php
include "../../utils/conn.php";
include "../../utils/verifyAndStartSession.php";

$query = "SELECT ID, Prenotante, CONCAT(PE.Nome, ' ', PE.Cognome) as NomePrenotante, Campo, Sport, DataInizio, Convalidatore, Attivita
			FROM PRENOTAZIONE PR
			JOIN PERSONA PE ON (PR.Prenotante = PE.CF)
			JOIN CAMPO C ON (PR.Campo = C.Codice);";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@event-calendar/build@4.0.3/dist/event-calendar.min.js"></script>
    <title>Gestisci Prenotazioni</title>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/logo.png" alt="Logo" class="h-20 w-auto">
                <h1 class="text-2xl font-bold">SportHub - Gestione Prenotazioni</h1>
            </div>
            <nav class="flex items-center gap-6">
                <a href="/dashboard.php" class="hover:text-gray-200">Dashboard</a>
                <a href="/pages/assemblee/visualizza.php" class="hover:text-gray-200">Assemblee</a>
                <a href="/pages/prenotazioni/prenota.php" class="hover:text-gray-200">Prenotazioni</a>
                <a href="/pages/private/datiPersonali.php" class="hover:text-gray-200">Dati Personali</a>
                <a href="/logout.php" class="text-red-400 hover:text-red-500">Logout</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
		<div class="bg-white p-6 rounded-xl shadow-md space-y-4">
			<div class="flex justify-between items-center">
				<h1 class="text-2xl font-bold">Calendario Prenotazioni</h1>

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

        <h2 class="text-3xl font-bold text-center mb-4 mt-6">Convalida la prenotazione</h2>

        <form action="../../utils/convalidaPrenotazione.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="NumeroPrenotazione" class="block text-lg font-semibold">Seleziona la prenotazione</label>
                <select name="NumeroPrenotazione" id="prenotazione" required class="mt-2 p-3 border rounded-md w-full">
                    <option value="">-- Seleziona --</option>
                    <?php foreach($result as $row): ?>
                        <option value="<?= $row['ID'] ?>"><?= $row["NomePrenotante"]." - ".$row["DataInizio"]." - ".$row["Campo"] ?></option>
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

		document.getElementById("calendar-view").addEventListener("change", function () {
			ec.setOption("view", this.value);
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
