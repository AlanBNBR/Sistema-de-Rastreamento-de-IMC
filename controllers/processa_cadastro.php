<?php
session_start();

require_once '../config/db.php';
require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
        header("Location: ../views/cadastro.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Formato de email inválido.";
        header("Location: ../views/cadastro.php");
        exit;
    }

    if (strlen($senha) < 6) {
        $_SESSION['error_message'] = "A senha deve ter no mínimo 6 caracteres.";
        header("Location: ../views/cadastro.php");
        exit;
    }

    if ($senha !== $confirmar_senha) {
        $_SESSION['error_message'] = "As senhas não coincidem.";
        header("Location: ../views/cadastro.php");
        exit;
    }


    try {
        $usuarioModel = new Usuario($pdo);
        
        if ($usuarioModel->buscarPorEmail($email)) {
            $_SESSION['error_message'] = "Este email já está cadastrado.";
            header("Location: ../views/cadastro.php");
            exit;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        if ($usuarioModel->criar($nome, $email, $senhaHash)) {
            header("Location: ../views/login.php?status=success");
            exit;
        } else {
            $_SESSION['error_message'] = "Ocorreu um erro ao criar a conta.";
            header("Location: ../views/cadastro.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
        header("Location: ../views/cadastro.php");
        exit;
    }

} else {
    header("Location: ../views/cadastro.php");
    exit;
}
?>