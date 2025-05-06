<?php
include '../../utils/conn.php';
include '../../utils/verifyAndStartSession.php';

$query = "SELECT NumProtocollo, Anno, Data, Oggetto, ODG, Testo FROM ATTO";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza gli atti</title>
</head>

<body>
    <h1>Visualizza gli atti</h1>
	<h2>Da qui puoi visualizzare gli atti</h2>
    
    <table border=1>
        <thead>
            <th>Numero di Protocollo</th>
            <th>Oggetto</th>
            <th>Data</th>
            <th>Ordine del giorno</th>
            <th>Testo</th>
        </thead>
        <tbody>
            <?php foreach($result as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['NumProtocollo'])."/".htmlspecialchars($row['Anno']) ?></td>
                    <td><?= htmlspecialchars($row['Oggetto']) ?></td>
                    <td><?= htmlspecialchars($row['Data']) ?></td>
                    <td><?= htmlspecialchars($row['ODG']) ?></td>
                    <td><?= htmlspecialchars($row['Testo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>