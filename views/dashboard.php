<?php
include '../db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM boards WHERE user_id=$user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de <?php echo $_SESSION['username']; ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            background-image: url('https://source.unsplash.com/random/1600x900');
            background-size: cover;
            background-position: center;
            color: #333;
            padding-top: 50px; /* Espacio para la barra de navegación */
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        .navbar-brand {
            font-weight: bold;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .dashboard-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .table {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 200px;
            width: 100%; /* Hacer que el gráfico ocupe el ancho completo */
            max-width: 400px; /* Ancho máximo del gráfico */
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard de <?php echo $_SESSION['username']; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="create_board_form.html">Crear Nuevo Tablero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="acceder_tablero.php">Ingresar a un tablero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h1 class="display-4">Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
            <p class="lead">Aquí puedes gestionar tus tableros y tareas.</p>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Acciones</th>
                    <th>Gráfica</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $board_id = $row['id'];
                        $sql_pending = "SELECT COUNT(*) as total FROM tasks WHERE board_id=$board_id AND status='pending'";
                        $result_pending = $conn->query($sql_pending);
                        $pending = $result_pending->fetch_assoc()['total'];

                        $sql_completed = "SELECT COUNT(*) as total FROM tasks WHERE board_id=$board_id AND status='completed'";
                        $result_completed = $conn->query($sql_completed);
                        $completed = $result_completed->fetch_assoc()['total'];

                        echo "<tr>
                                <td>".$row['id']."</td>
                                <td>".$row['name']."</td>
                                <td>".$row['code']."</td>
                                <td>
                                    <a href='delete_board.php?id=".$row['id']."' class='btn btn-danger btn-sm'>Eliminar</a>
                                </td>
                                <td class='chart-container'>
                                    <canvas id='chart".$board_id."' width='200' height='100'></canvas>
                                    <script>
                                        var ctx = document.getElementById('chart".$board_id."').getContext('2d');
                                        var chart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: ['Pendientes', 'Completadas'],
                                                datasets: [{
                                                    label: '# de Tareas',
                                                    data: [".$pending.", ".$completed."],
                                                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                                                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    </script>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay tableros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
