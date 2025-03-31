<?php

    include 'conn.php';

    if(!isset($_SESSION("Email"))){
        header("../login.html");
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

        if(password_verify($Password, $Password_DB)){
            session_start();
            $_SESSION['Email'] = $Email;
            
            header('Location: ../homepage.html');
        }else{
            echo "Invalid credentials";
        }   


    }   

?>