<?php


include "../conn.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = htmlentities($_POST["name"]);
    $email = htmlentities($_POST["email"]);
    $phone = htmlentities($_POST["phone"]);
    $mess = htmlentities($_POST["mess"]);


    $query = "INSERT INTO MESSAGES VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $phone, $mess);
    $stmt->execute();

    $result = $stmt->get_result();

    if($conn->affected_rows == 0){
        die("si è verificato un problema durante l'invio del messaggio");
    }

    if($conn->affected_rows > 1){
        die("si è verificato un problema durante l'esecuzione");
    }

    $stmt->close();
    $conn->close();
}
?>