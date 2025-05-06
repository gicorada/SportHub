<!-- 

QUI CI VANNO
CALENDARIO
FORM PRENOTAZIONE

-->
<?php
    include '../../utils/verifyAndStartSession.php';
    include "../../utils/conn.php";

    $query = "SELECT * FROM CAMPO";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 0){
        echo "Nessun campo trovato";
    }

    $stmt->close();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PRENOTA IL TUO CAMPO</title>

        <link rel="stylesheet" href="../../style/prenotazione.css">
    </head>
    <body>    
        <form action="/utils/prenotazione/aggiungiPrenotazione.php" method="post">
            <label for="field">SCEGLI IL CAMPO</label>

            <select name="field" id="field">
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row["Codice"] ?>"><?= $row["Codice"]." - ".$row["Sport"] ?></option>";
                <?php endwhile; ?>
            </select>

            <h2><label for="date">SCEGLI LA DATA</label></h2>
            <input type="date" name="date" id="date" required>

            <button type="submit" class="btn btn-primary">PRENOTA</button>
        </form>
    </body>
</html>