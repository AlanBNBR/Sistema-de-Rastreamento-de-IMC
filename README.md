#  Rastreador de IMC (Projeto Web 2)

Este é um projeto acadêmico desenvolvido para a disciplina de Web 2, seguindo os requisitos do "Trabalho T1 - WEB 2 - Trimestre 3".

O objetivo foi desenvolver um sistema web completo utilizando PHP puro e MySQL. O sistema permite que usuários se cadastrem, autentiquem e mantenham um histórico de suas medições de IMC (Índice de Massa Corporal), com um gráfico que exibe a progressão do peso ao longo do tempo.

---

##  Funcionalidades Implementadas

O sistema atende a todos os requisitos obrigatórios:

* **Autenticação de Usuários:**
    * Cadastro de novas contas (com hash de senha usando `password_hash`).
    * Sistema de Login e Logout.
    * Controle de acesso com Sessões PHP (`$_SESSION`).

* **CRUD (Create, Read, Update, Delete):**
    * Implementado um CRUD completo para a entidade "Registros de IMC".
    * **Create:** Usuário pode registrar novas medições (peso e altura).
    * **Read:** Usuário pode visualizar seu histórico completo em uma tabela.
    * **Delete:** Usuário pode excluir registros do seu histórico.

* **Relatórios:**
    * Geração de um relatório visual (gráfico de linha) que mostra a evolução do peso do usuário ao longo do tempo, utilizando a biblioteca Chart.js.

* **Segurança:**
    * Proteção contra SQL Injection (uso de PDO com *prepared statements*).
    * Validação de todos os dados no Back-end.
    * Proteção contra XSS (uso de `htmlspecialchars` ao exibir dados do usuário).

* **Front-end:**
    * Interface responsiva utilizando o framework **Bootstrap 5**.
    * Validação de formulários no cliente (HTML5 `required`).

---

##  Tecnologias Utilizadas

* **Back-end:** PHP 8.0 (Puro)
* **Banco de Dados:** MySQL
* **Conexão DB:** PDO
* **Front-end:** HTML5, CSS3, JavaScript (ES6+)
* **Bibliotecas:** Bootstrap 5, Chart.js
* **Servidor Local:** XAMPP (Apache)

---

##  Instruções de Instalação e Execução

Para executar este projeto localmente, siga os passos abaixo:

1.  **Pré-requisitos:**
    * Ter um ambiente de servidor local como XAMPP, WAMP ou MAMP instalado.
    * Este guia assume o uso do **XAMPP**.

2.  **Clonar o Repositório:**
    ```bash
    git clone https://github.com/AlanBNBR/Sistema-de-Rastreamento-de-IMC
    ```
    *Ou baixe o arquivo `.zip` e descompacte-o.*

3.  **Mover Arquivos:**
    * Mova a pasta do projeto (ex: `meuimc`) para dentro da pasta `htdocs` do seu XAMPP (normalmente localizada em `C:\xampp\htdocs\`).

4.  **Iniciar Servidores:**
    * Abra o Painel de Controle do XAMPP e inicie os módulos **Apache** e **MySQL**.

5.  **Criar o Banco de Dados:**
    * Acesse o `phpMyAdmin` pelo seu navegador: `http://localhost/phpmyadmin`
    * Crie um novo banco de dados com o nome `imc_tracker`.
    * Selecione o banco `imc_tracker` recém-criado.
    * Clique na aba **Importar**.
    * Selecione o arquivo `database.sql` (ou `arquivo.sql` ) que está na raiz deste repositório e execute a importação.

6.  **Configurar Conexão (Se Necessário):**
    * Este projeto está configurado para o padrão do XAMPP (usuário `root`, sem senha).
    * Se o seu MySQL tiver uma senha diferente, edite o arquivo:
        `/config/db.php`
    * Altere a constante `DB_PASS` para a sua senha:
        ```php
        define('DB_PASS', 'SUA_SENHA_AQUI');
        ```

7.  **Acessar o Projeto:**
    * Abra seu navegador e acesse: `http://localhost/meuimc/`
    * Você será redirecionado para a página de login. Crie uma nova conta para começar a usar.

---

##  Diagrama do Banco de Dados

Conforme solicitado, esta é a estrutura do banco de dados `imc_tracker`:

### Tabela `usuarios`
Armazena as informações de login e perfil.
```sql
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL COMMENT 'Armazena o hash (password_hash)',
  `data_cadastro` DATETIME DEFAULT CURRENT_TIMESTAMP
);