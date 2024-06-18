<?php
include '../db/db.php';

if (isset($_GET['id']) && isset($_GET['board_id'])) { // Asegúrate de que ambos parámetros están definidos en la URL
    $id = $_GET['id'];
    $board_id = $_GET['board_id']; // Obtener el ID del tablero de la URL

    $sql = "SELECT * FROM tasks WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Tarea no encontrada.";
        exit;
    }
} else {
    echo "Faltan parámetros necesarios.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Tarea</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Actualizar Tarea</h1>
        <form action="update_task.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>"> <!-- Añadir el ID del tablero -->

            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Estado:</label>
                <select class="form-control" id="status" name="status">
                    <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completada</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Actualizar Tarea</button>
            <button type="button" class="btn btn-danger" onclick="cancelUpdate(<?php echo $board_id; ?>)">Cancelar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function cancelUpdate(boardId) {
            window.location.href = 'board.php?id=' + boardId; // Redirigir a la página del tablero con el ID del tablero
        }
    </script>
</body>

</html>
