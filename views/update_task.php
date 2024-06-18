<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "UPDATE tasks SET title='$title', description='$description', status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Tarea actualizada con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
