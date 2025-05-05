<?php
    include 'conn.php';
    session_start();

    if(isset($_SESSION["Email"])){
        header("Location: ../dashboard.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Email = htmlentities($_POST['Email']);
        $Password = htmlentities($_POST['Password']);

        $stmt = $conn->prepare("SELECT Password FROM PERSONA WHERE Email = ?");
        $stmt->bind_param("s", $Email);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $Password_DB = $row['Password'];

        if(password_verify($Password, $Password_DB)) {
            $_SESSION['Email'] = $Email;

            $stmt = $conn->prepare("SELECT N.Carica FROM PERSONA P JOIN NOMINA N ON (P.CF = N.Persona) WHERE P.Email = ? AND DataFine = NULL AND (CURRENT_DATE BETWEEN DataInizio AND DataFine)");
            $stmt->bind_param("s", $Email);
            $stmt->execute();

            $result = $stmt->get_result();
            $rows = $result->fetch_all();

            $ruoli = [];
            foreach($rows as $ruolo) {
                array_push($ruoli, $ruolo[0]);
            }

            $_SESSION['ruoli'] = $ruoli;

            $stmt = $conn->prepare("SELECT CF FROM PERSONA P WHERE P.Email = ?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $_SESSION['CF'] = $row['CF'];

            header('Location: ../dashboard.php');
        } else {
            echo "Invalid credentials";
        }   
    }
?>