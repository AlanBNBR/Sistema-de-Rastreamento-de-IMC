<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/db.php';
require_once '../models/RegistroIMC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_SESSION['usuario_id'];
    $peso = trim($_POST['peso']);
    $altura = trim($_POST['altura']);

    if (empty($peso) || empty($altura)) {
        $_SESSION['error_message'] = "Peso e altura são obrigatórios.";
        header("Location: ../views/dashboard.php");
        exit;
    }

    $peso = str_replace(',', '.', $peso);
    $altura = str_replace(',', '.', $altura);

    if (!is_numeric($peso) || !is_numeric($altura) || $peso <= 0 || $altura <= 0) {
        $_SESSION['error_message'] = "Peso e altura devem ser números positivos.";
        header("Location: ../views/dashboard.php");
        exit;
    }

    $peso = (float)$peso;
    $altura = (float)$altura;

    $imc_calculado = round($peso / ($altura * $altura), 1);

    try {
        $registroModel = new RegistroIMC($pdo);
        
        if ($registroModel->criar($id_usuario, $peso, $altura, $imc_calculado)) {
            $_SESSION['success_message'] = "IMC registrado com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao registrar o IMC.";
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
    }

    header("Location: ../views/dashboard.php");
    exit;

} else {
    header("Location: ../views/dashboard.php");
    exit;
}
?>