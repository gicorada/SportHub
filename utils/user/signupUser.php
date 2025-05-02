<?php
    include '../conn.php';

    if(isset($_SESSION["Email"])){
        header("Location: ../../index.html");
        exit();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $CF = $_POST['CF'];
        $Email = $_POST['Email'];
        $Nome = $_POST['Nome'];
        $Cognome = $_POST['Cognome'];
        $Password = $_POST['Password'];

        $passwordHash = password_hash($Password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO PERSONA (CF, Nome, Cognome, Email, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $CF, $Nome, $Cognome, $Email, $passwordHash);

        $stmt->execute();

        if($stmt->affected_rows === 0) die('Errore nella creazione dell\'utente');
        else {
            echo 'Utente creato con successo';
            header('Location: ../../login.html');
        }
    }
?>