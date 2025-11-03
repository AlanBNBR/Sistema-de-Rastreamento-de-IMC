CREATE SCHEMA IF NOT EXISTS `imc_tracker`
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `imc_tracker`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `data_cadastro` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `registros_imc` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT NOT NULL,
  `peso` DECIMAL(5, 2) NOT NULL,
  `altura` DECIMAL(3, 2) NOT NULL,
  `imc_calculado` DECIMAL(4, 2) NOT NULL,
  `data_registro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  CONSTRAINT `fk_usuario_registro`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);