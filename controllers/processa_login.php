<?php
session_start();

require_once '../config/db.php';
require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $_SESSION['error_message'] = "Email e senha são obrigatórios.";
        header("Location: ../views/login.php");
        exit;
    }

    try {
        $usuarioModel = new Usuario($pdo);
        
        $usuario = $usuarioModel->obterPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            header("Location: ../views/dashboard.php");
            exit;

        } else {
            $_SESSION['error_message'] = "Email ou senha inválidos.";
            header("Location: ../views/login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
        header("Location: ../views/login.php");
        exit;
    }

} else {
    header("Location: ../views/login.php");
    exit;
}
?>