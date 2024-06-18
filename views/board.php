<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $board_id = $_GET['id'];

    $sql_board = "SELECT * FROM boards WHERE id=$board_id";
    $result_board = $conn->query($sql_board);
    $board = $result_board->fetch_assoc();

    $sql_tasks = "SELECT * FROM tasks WHERE board_id=$board_id";
    $result_tasks = $conn->query($sql_tasks);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero: <?php echo htmlspecialchars($board['name']); ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 50px;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .task-card {
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .task-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        #chat {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        #messages {
            height: 200px;
            overflow-y: scroll;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 10px;
            padding: 5px;
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .message .user {
            font-weight: bold;
            color: #007bff;
        }

        .message .content {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tablero: <?php echo htmlspecialchars($board['name']); ?></h1>
        <a href="crearTarea.php?board_id=<?php echo $board['id']; ?>" class="btn btn-primary mb-3">Crear Nueva Tarea</a>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Volver al Dashboard</a>

        <div class="row">
            <?php
            if ($result_tasks->num_rows > 0) {
                while ($row = $result_tasks->fetch_assoc()) {
                    echo "<div class='col-lg-4 col-md-6'>
                            <div class='card task-card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>
                                    <p class='card-text'>" . htmlspecialchars($row['description']) . "</p>
                                    <p class='card-text'><strong>Estado:</strong> " . htmlspecialchars($row['status']) . "</p>
                                    <a href='actualizacion.php?id=" . $row['id'] . "&board_id=" . $board_id . "' class='btn btn-sm btn-info mr-2'>Editar</a>
                                    <a href='eliminar.php?id=" . $row['id'] . "&board_id=" . $board_id . "' class='btn btn-sm btn-danger'>Eliminar</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p class='text-center'>No hay tareas en este tablero.</p>";
            }
            ?>
        </div>

        <!-- Chat -->
        <div id="chat">
            <h2>Chat</h2>
            <div id="messages"></div>
            <div class="input-group mb-3">
                <input type="text" id="username" placeholder="Tu nombre" class="form-control">
                <input type="text" id="messageInput" placeholder="Tu mensaje" class="form-control">
                <div class="input-group-append">
                    <button id="sendMessage" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        const conn = new WebSocket('ws://localhost:8080');
        const board_id = "<?php echo $board['id']; ?>";

        conn.onopen = () => {
            console.log('Conexión establecida');
        };

        conn.onerror = (error) => {
            console.error('Error en la conexión WebSocket:', error);
        };

        conn.onmessage = (e) => {
            console.log('Mensaje recibido:', e.data);
            const data = JSON.parse(e.data);
            displayMessage(data.user, data.message);
        };

        document.getElementById('sendMessage').addEventListener('click', () => {
            const username = document.getElementById('username').value.trim();
            const message = document.getElementById('messageInput').value.trim();
            if (username && message) {
                const data = JSON.stringify({ board_id, user: username, message });
                console.log('Enviando mensaje:', data);
                conn.send(data);
                displayMessage(username, message); // Mostrar el mensaje del usuario actual
                document.getElementById('messageInput').value = '';
            }
        });

        function displayMessage(user, message) {
            const messagesContainer = document.getElementById('messages');
            const messageElement = document.createElement('div');
            messageElement.classList.add('message');
            messageElement.innerHTML = `<span class="user">${user}:</span> <span class="content">${message}</span>`;
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll hasta el último mensaje
        }

        // Añadir mensaje de prueba al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            displayMessage('Sistema', 'Mensaje de prueba, SOCKET LISTO');
        });
    </script>
</body>

</html>
