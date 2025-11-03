<?php
class RegistroIMC {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function criar($id_usuario, $peso, $altura, $imc_calculado) {
        $sql = "INSERT INTO registros_imc (id_usuario, peso, altura, imc_calculado) 
                VALUES (:id_usuario, :peso, :altura, :imc_calculado)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);
        $stmt->bindParam(':imc_calculado', $imc_calculado);
        
        return $stmt->execute();
    }

    public function listarPorUsuario($id_usuario) {
        $sql = "SELECT id, peso, altura, imc_calculado, data_registro 
                FROM registros_imc 
                WHERE id_usuario = :id_usuario 
                ORDER BY data_registro DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function deletar($id_registro, $id_usuario) {
        $sql = "DELETE FROM registros_imc 
                WHERE id = :id_registro AND id_usuario = :id_usuario";
        
        $stmt = $this->pdo->prepare($sql); 
        
        $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function listarParaGrafico($id_usuario) {
        $sql = "SELECT peso, data_registro 
                FROM registros_imc 
                WHERE id_usuario = :id_usuario 
                ORDER BY data_registro ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>