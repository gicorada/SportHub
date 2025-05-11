<?php
    include "../../utils/conn.php";
    include "../../utils/verifyAndStartSession.php";

    $ruoli = $_SESSION["ruoli"];
	if(!in_array('Presidente', $ruoli) && !in_array('Consigliere', $ruoli) && !in_array('Socio', $ruoli)) {
		die("Permessi insufficienti");
	}

    $queryAtto = "SELECT NumProtocollo, Anno FROM ATTO";
    $stmtAtto = $conn->prepare($queryAtto);
    $stmtAtto->execute();
    $resultAtto = $stmtAtto->get_result();
    if($resultAtto->num_rows == 0){
        echo "Nessun atto trovato";
    }
    $stmtAtto->close();

    $queryPersone = "SELECT CF, Nome, Cognome FROM PERSONA";
    $stmtPersone = $conn->prepare($queryPersone);
    $stmtPersone->execute();
    $resultPersone = $stmtPersone->get_result();
    if($resultPersone->num_rows == 0){
        echo "Nessuna persona trovata";
    }
    $stmtPersone->close();

    $queryCarica = "SELECT Nome FROM CARICA";
    $stmtCarica = $conn->prepare($queryCarica);
    $stmtCarica->execute();
    $resultCarica = $stmtCarica->get_result();
    if($resultCarica->num_rows == 0){
        echo "Nessuna carica trovata";
    }
    $stmtCarica->close();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
    <title>Gestisci Atti</title>
</head>
<body class="bg-gray-50">
    <?php 
        $titleHeader = "Gestisci Atti";
        $activeHeader = "gestisci-atti";
        include "../../partials/header.php";
    ?>

    <main class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-center mb-4 mt-6">Aggiungi un atto</h2>

        <form action="/utils/atti/aggiungi.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="anno" class="block text-gray-700 text-sm font-bold mb-2">Anno:</label>
                <input type="text" id="anno" name="anno" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="data" class="block text-gray-700 text-sm font-bold mb-2">Data:</label>
                <input type="date" id="data" name="data" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="oggetto" class="block text-gray-700 text-sm font-bold mb-2">Oggetto:</label>
                <input type="text" id="oggetto" name="oggetto" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="odg" class="block text-gray-700 text-sm font-bold mb-2">Ordine del Giorno:</label>
                <input type="text" id="odg" name="odg" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="testo" class="block text-gray-700 text-sm font-bold mb-2">Testo:</label>
                <textarea id="testo" name="testo" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Aggiungi Atto</button>
            </div>

        </form>

        <?php if($resultAtto->num_rows == 0): ?>
            <p class="text-red-500 text-center">Nessun atto trovato. Aggiungi un atto per procedere.</p>
        <?php else: ?>

            <h2 class="text-3xl font-bold text-center mb-4 mt-6">Gestisci Nomine</h2>

            <form action="/utils/atti/aggiungiNomina.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label for="atto" class="block text-gray-700 text-sm font-bold mb-2">ID Atto:</label>
                    <select name="atto" id="atto" class="mt-2 p-2 border rounded-md">
                        <?php while($row = $resultAtto->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['NumProtocollo'])."/".htmlspecialchars($row['Anno']) ?>"><?= htmlspecialchars($row['NumProtocollo'])."/".htmlspecialchars($row['Anno']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
                    <select name="persona" id="persona" class="mt-2 p-2 border rounded-md">
                        <?php while($row = $resultPersone->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['CF']) ?>"><?= htmlspecialchars($row['Nome'])." ".htmlspecialchars($row['Cognome'])." - ".htmlspecialchars($row['CF']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="ruolo" class="block text-gray-700 text-sm font-bold mb-2">Ruolo:</label>
                    <select name="carica" id="carica" class="mt-2 p-2 border rounded-md">
                        <?php while($row = $resultCarica->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['Nome']) ?>"><?= htmlspecialchars($row['Nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dataInizio" class="block text-gray-700 text-sm font-bold mb-2">Data Inizio:</label>
                    <input type="date" id="dataInizio" name="dataInizio" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="dataFine" class="block text-gray-700 text-sm font-bold mb-2">Data Fine:</label>
                    <input type="date" id="dataFine" name="dataFine" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Aggiungi Nomina</button>
                </div>
            </form>

        <?php endif; ?>

    </main>
</body>
</html>
