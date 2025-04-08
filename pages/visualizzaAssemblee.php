<!--

QUI CI VANNO
CALENDARIO CON ASSEMBLEE

-->

<?php
include "../utils/conn.php";
include "../utils/verifyAndStartSession.php";
/*require_once "../utils/requireCalendar.php";

$calendar = new Calendar;
$calendar->stylesheet();
*/

$query = "SELECT Data, ODG, Descrizione, CONCAT(P.Nome, ' ', P.Cognome) as Convocatore
			FROM ASSEMBLEA A
			JOIN PERSONA P ON (A.Convocatore = P.CF)
			/*LEFT JOIN PARTECIPAZIONE_ASSEMBLEA P_A ON (P.CF = P_A.Persona)*/;";

$stmt = $conn->prepare($query);
/*$stmt->bind_param("s", $_SESSION["CF"]);*/
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$result = $conn->query($query);

foreach($result as $row){
    var_dump($row);
}

/*(new Calendar)->display();*/

?>