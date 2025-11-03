<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Acesso não autorizado']);
    exit;
}

require_once '../config/db.php';
require_once '../models/RegistroIMC.php';

$id_usuario = $_SESSION['usuario_id'];
$registroModel = new RegistroIMC($pdo);

$dadosGrafico = $registroModel->listarParaGrafico($id_usuario);

$labels = [];
$data = [];

foreach ($dadosGrafico as $registro) {
    $labels[] = date('d/m', strtotime($registro['data_registro']));
    $data[] = (float)$registro['peso'];
}

$resposta = [
    'labels' => $labels,
    'data' => $data
];

echo json_encode($resposta);
exit;
?>