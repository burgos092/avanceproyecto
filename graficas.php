<?php
include 'db/db.php';

$sql_pending = "SELECT COUNT(*) as total FROM tasks WHERE status='pending'";
$result_pending = $conn->query($sql_pending);
$pending = $result_pending->fetch_assoc()['total'];

$sql_completed = "SELECT COUNT(*) as total FROM tasks WHERE status='completed'";
$result_completed = $conn->query($sql_completed);
$completed = $result_completed->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gráficas de Tareas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Gráficas de Tareas</h1>
    <canvas id="taskChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('taskChart').getContext('2d');
        var taskChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Completadas'],
                datasets: [{
                    label: '# de Tareas',
                    data: [<?php echo $pending; ?>, <?php echo $completed; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
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
</body>
</html>
