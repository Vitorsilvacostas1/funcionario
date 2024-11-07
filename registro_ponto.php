<?php
// Incluindo a conexão com o banco de dados
include 'conexao.php';

// Verificando a conexão e suprimindo mensagens de erro visíveis para o usuário
if ($conn->connect_error) {
    // Aqui você pode registrar o erro em um arquivo de log se necessário
    // e não exibir essa mensagem para o usuário
    die("Erro na conexão com o banco de dados.");
}

// Processando o formulário de registro de ponto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificando se os campos foram preenchidos corretamente
    if (isset($_POST['nome']) && isset($_POST['cargo'])) {
        $nome = $_POST['nome'];
        $cargo = $_POST['cargo'];

        // Inserindo os dados no banco
        $sql = "INSERT INTO tbl_funcionario (fun_nome, fun_cargo) VALUES ('$nome', '$cargo')";
        
        if ($conn->query($sql) !== TRUE) {
            // Em caso de erro, mostramos uma mensagem para o usuário
            echo "<div class='error-message'>Erro: " . $conn->error . "</div>";
        }
    }
}

// Buscando todos os funcionários e registros de ponto
$registros = $conn->query("SELECT * FROM tbl_registro");
$funcionarios = $conn->query("SELECT * FROM tbl_funcionario");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Ponto</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos gerais para centralização e layout */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 200px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
            text-align: center;
        }

        h1, h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .form-container {
            margin-bottom: 40px;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="date"], input[type="time"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #1976D2;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .actions a {
            color: #2196F3;
            text-decoration: none;
            margin: 0 5px;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Controle de Ponto</h1>

        <!-- Formulário para registrar ponto -->
        <div class="form-container">
            <h2>Registrar Ponto</h2>
            <form method="POST">
                <label for="fun_codigo">Funcionário:</label>
                <select name="fun_codigo" id="fun_codigo" required>
                    <?php while ($func = $funcionarios->fetch_assoc()) { ?>
                        <option value="<?php echo $func['fun_codigo']; ?>"><?php echo $func['fun_nome']; ?></option>
                    <?php } ?>
                </select>

                <label for="data">Data:</label>
                <input type="date" name="data" id="data" required>

                <label for="hora">Hora:</label>
                <input type="time" name="hora" id="hora" required>

                <button type="submit">Registrar</button>
            </form>
        </div>

        <h2>Funcionários Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($func = $funcionarios->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $func['fun_codigo']; ?></td>
                        <td><?php echo $func['fun_nome']; ?></td>
                        <td><?php echo $func['fun_cargo']; ?></td>
                        <td class="actions">
                            <a href="editar_funcionario.php?id=<?php echo $func['fun_codigo']; ?>">Editar</a> | 
                            <a href="index.php?excluir=<?php echo $func['fun_codigo']; ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Registros de Ponto</h2>
        <table>
            <thead>
                <tr>
                    <th>Código do Registro</th>
                    <th>Código do Funcionário</th>
                    <th>Data</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($registro = $registros->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $registro['res_codigo']; ?></td>
                        <td><?php echo $registro['fun_codigo']; ?></td>
                        <td><?php echo $registro['res_data']; ?></td>
                        <td><?php echo $registro['res_hora']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="index.php" class="back-button">Voltar</a>

        <footer>
            <p>&copy; 2024 Sistema de Controle de Ponto</p>
        </footer>
    </div>
</body>
</html>
