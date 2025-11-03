<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/db.php';
require_once '../models/RegistroIMC.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_registro = $_GET['id'];
    $id_usuario = $_SESSION['usuario_id'];

    try {
        $registroModel = new RegistroIMC($pdo);
        
        if ($registroModel->deletar($id_registro, $id_usuario)) {
            $_SESSION['success_message'] = "Registro excluído com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao excluir registro ou permissão negada.";
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
    }

} else {
    $_SESSION['error_message'] = "ID do registro não fornecido.";
}

header("Location: ../views/dashboard.php");
exit;
?>