// create_board.php
<?php
include '../db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $code = generateCode();
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO boards (code, name, user_id) VALUES ('$code', '$name', $user_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo tablero creado con éxito. Código: $code";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
