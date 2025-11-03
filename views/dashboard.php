<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'];
$idUsuario = $_SESSION['usuario_id'];

require_once '../config/db.php';
require_once '../models/RegistroIMC.php';

$registroModel = new RegistroIMC($pdo);
$historico = $registroModel->listarPorUsuario($idUsuario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rastreador de IMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">MeuIMC</a>
            <div class="ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../controllers/logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Bem-vindo(a), <?php echo htmlspecialchars($nomeUsuario); ?>!</h1>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <div class="row g-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Registrar Nova Medição</h4>
                    </div>
                    <div class="card-body">
                        <form action="../controllers/processa_registro_imc.php" method="POST">
                            <div class="mb-3">
                                <label for="peso" class="form-label">Seu Peso (kg)</label>
                                <input type="text" class="form-control" id="peso" name="peso" placeholder="ex: 75.5" required>
                            </div>
                            <div class="mb-3">
                                <label for="altura" class="form-label">Sua Altura (m)</label>
                                <input type="text" class="form-control" id="altura" name="altura" placeholder="ex: 1.78" required>
                                <small class="form-text text-muted">Use ponto ou vírgula (ex: 1.78 ou 1,78)</small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Salvar Registro</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <h4>Seu Histórico</h4>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark" style="position: sticky; top: 0;">
                            <tr>
                                <th>Data</th>
                                <th>Peso (kg)</th>
                                <th>Altura (m)</th>
                                <th>IMC</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($historico)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum registro encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($historico as $registro): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i', strtotime($registro['data_registro'])); ?></td>
                                        <td><?php echo number_format($registro['peso'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($registro['altura'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($registro['imc_calculado'], 1, ',', '.'); ?></td>
                                        <td>
                                            <a href="../controllers/processa_delete_imc.php?id=<?php echo $registro['id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Tem certeza que deseja excluir este registro?');">
                                               Excluir
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><div class="row mt-5 mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Progresso do Peso</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="meuGraficoDePeso"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div> <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            async function fetchChartData() {
                try {
                    const response = await fetch('../controllers/get_chart_data.php');
                    if (!response.ok) {
                        throw new Error('Falha ao buscar dados do gráfico');
                    }
                    
                    const dados = await response.json();
                    
                    if (dados.labels && dados.data && dados.data.length > 1) {
                        renderChart(dados.labels, dados.data);
                    } else {
                        const ctx = document.getElementById('meuGraficoDePeso').getContext('2d');
                        ctx.font = '16px Arial';
                        ctx.fillStyle = '#666';
                        ctx.textAlign = 'center';
                        ctx.fillText('Adicione 2+ registros para ver o gráfico.', ctx.canvas.width / 2, 50);
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            function renderChart(labels, data) {
                const ctx = document.getElementById('meuGraficoDePeso').getContext('2d');
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peso (kg)',
                            data: data,
                            borderColor: 'rgb(0, 123, 255)',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: false,
                                title: {
                                    display: true,
                                    text: 'Peso (kg)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Data da Medição'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }

            fetchChartData();
        });
    </script>
</body>
</html>