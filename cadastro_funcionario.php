<?php
include 'conexao.php';

// Habilitar exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar a conexão com o banco de dados
if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Formulário enviado!";  // Para depuração

    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];

    // Usando prepared statement para evitar SQL Injection
    $sql = "INSERT INTO tbl_funcionario (fun_nome, fun_cargo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar se a consulta foi preparada corretamente
    if ($stmt === false) {
        die('Erro ao preparar a consulta: ' . $conn->error);
    }

    $stmt->bind_param("ss", $nome, $cargo);

    if ($stmt->execute()) {
        echo "<div class='message success'>Novo funcionário cadastrado com sucesso!</div>";
    } else {
        echo "<div class='message error'>Erro: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos CSS continuam o mesmo */
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Funcionário</h2>
        
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required placeholder="Digite o nome do funcionário">

            <label for="cargo">Cargo:</label>
            <input type="text" name="cargo" id="cargo" required placeholder="Digite o cargo do funcionário">

            <button type="submit">Cadastrar</button>
        </form>
        
        <footer>
            <p>&copy; 2024 Sistema de Cadastro de Funcionário</p>
        </footer>
    </div>
</body>
</html>
