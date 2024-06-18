<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    $sql = "SELECT * FROM boards WHERE code='$code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $board = $result->fetch_assoc();
        header("Location: board.php?id=" . $board['id']);
    } else {
        echo "Código de tablero no válido";
    }

    $conn->close();
}
?>
