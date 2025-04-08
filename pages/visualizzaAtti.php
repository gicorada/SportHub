<?php
include "../utils/conn.php";
include "../utils/verifyAndStartSession.php";

$query = "SELECT NumProtocollo, Anno, Data, Oggetto, ODG, Testo FROM ATTO A";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$result = $conn->query($query);

foreach($result as $row){
    var_dump($row);
}
?>