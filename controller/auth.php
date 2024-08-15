<?php
    session_start();

    if (!isset($_SESSION["username"]) || !isset($_SESSION["userID"]) || !isset($_SESSION["userRole"]))
    {
        header("Location: ../");
        exit();
    }
?>