<?php
include '../db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['id'])) {
    $board_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM boards WHERE id=$board_id AND user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "Tablero eliminado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: dashboard.php");
}
?>