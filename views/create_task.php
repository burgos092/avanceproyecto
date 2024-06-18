<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $board_id = $_POST['board_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO tasks (title, description, status, board_id) VALUES ('$title', '$description', '$status', $board_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Nueva tarea creada con éxito";
        header("Location: board.php?id=" . $board_id);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
