<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$query = "SELECT NumProtocollo, Anno, Data, Oggetto, ODG, Testo FROM ATTO";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza gli atti</title>
    <script src="https://cdn.tailwindcss.com"></script>
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50">
    <?php 
        $titleHeader = "Visualizza Atti";
        $activeHeader = "visualizza-atti";
        include "../../partials/header.php";
    ?>

	<main class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">		
		<table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th>Numero di Protocollo</th>
                    <th>Oggetto</th>
                    <th>Data</th>
                    <th>Ordine del giorno</th>
                    <th>Testo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result as $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['NumProtocollo'])."/".htmlspecialchars($row['Anno']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Oggetto']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Data']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['ODG']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Testo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>