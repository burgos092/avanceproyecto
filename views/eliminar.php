<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM tasks WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Tarea eliminada con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
