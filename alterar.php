<?php
include 'conexao.php';

// Verificar se há um ID para editar ou excluir um funcionário
if (isset($_GET['id'])) {
    $fun_codigo = $_GET['id'];

    // Buscar o funcionário para preencher o formulário de edição
    $sql = "SELECT * FROM tbl_funcionario WHERE fun_codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fun_codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $funcionario = $result->fetch_assoc();

    // Verificar se o funcionário existe
    if (!$funcionario) {
        echo "<div class='message'>Funcionário não encontrado!</div>";
        exit;
    }
}

// Atualizar os dados do funcionário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $fun_codigo = $_POST['fun_codigo'];
    $fun_nome = $_POST['fun_nome'];
    $fun_cargo = $_POST['fun_cargo'];

    // Atualizar o funcionário no banco de dados
    $sql = "UPDATE tbl_funcionario SET fun_nome = ?, fun_cargo = ? WHERE fun_codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fun_nome, $fun_cargo, $fun_codigo);

    if ($stmt->execute()) {
        echo "<div class='message'>Funcionário atualizado com sucesso!</div>";
    } else {
        echo "<div class='message'>Erro: " . $stmt->error . "</div>";
    }
}

// Excluir o funcionário
if (isset($_GET['excluir'])) {
    $fun_codigo = $_GET['excluir'];
    $sql = "DELETE FROM tbl_funcionario WHERE fun_codigo = ?";
    
    // Usando prepared statement para excluir
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fun_codigo);

    if ($stmt->execute()) {
        echo "<div class='message'>Funcionário excluído com sucesso!</div>";
        header("Location: alterar.php");  // Redireciona para evitar o reenvio do formulário
        exit;
    } else {
        echo "<div class='message'>Erro ao excluir: " . $stmt->error . "</div>";
    }
}

// Excluir um registro de ponto
if (isset($_GET['excluir_ponto'])) {
    $res_codigo = $_GET['excluir_ponto'];
    $sql = "DELETE FROM tbl_registro WHERE res_codigo = ?";
    
    // Usando prepared statement para excluir
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $res_codigo);

    if ($stmt->execute()) {
        echo "<div class='message'>Registro de ponto excluído com sucesso!</div>";
        header("Location: alterar.php");  // Redireciona para evitar o reenvio do formulário
        exit;
    } else {
        echo "<div class='message'>Erro ao excluir registro de ponto: " . $stmt->error . "</div>";
    }
}

// Buscar todos os registros de ponto
$sql_registros = "SELECT * FROM tbl_registro";
$result_registros = $conn->query($sql_registros);

// Buscar todos os funcionários
$sql_funcionarios = "SELECT * FROM tbl_funcionario";
$result_funcionarios = $conn->query($sql_funcionarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Alterar Funcionário</h1>

        <!-- Formulário para editar funcionário -->
        <?php if (isset($funcionario)) { ?>
            <form method="POST">
                <input type="hidden" name="fun_codigo" value="<?php echo $funcionario['fun_codigo']; ?>">

                <label for="fun_nome">Nome:</label>
                <input type="text" id="fun_nome" name="fun_nome" value="<?php echo $funcionario['fun_nome']; ?>" required>

                <label for="fun_cargo">Cargo:</label>
                <input type="text" id="fun_cargo" name="fun_cargo" value="<?php echo $funcionario['fun_cargo']; ?>" required>

                <button type="submit" name="update">Atualizar</button>
            </form>

            <h2>Excluir Funcionário</h2>
            <p>Deseja excluir este funcionário? <a href="alterar.php?excluir=<?php echo $funcionario['fun_codigo']; ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a></p>
        <?php } else { ?>
            <div class="message">Funcionário não encontrado!</div>
        <?php } ?>

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
                <?php while ($func = $result_funcionarios->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $func['fun_codigo']; ?></td>
                        <td><?php echo $func['fun_nome']; ?></td>
                        <td><?php echo $func['fun_cargo']; ?></td>
                        <td class="actions">
                            <a href="alterar.php?id=<?php echo $func['fun_codigo']; ?>">Alterar</a> | 
                            <a href="alterar.php?excluir=<?php echo $func['fun_codigo']; ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Registros de Ponto</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Código Funcionário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($registro = $result_registros->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $registro['res_codigo']; ?></td>
                        <td><?php echo $registro['res_data']; ?></td>
                        <td><?php echo $registro['res_hora']; ?></td>
                        <td><?php echo $registro['fun_codigo']; ?></td>
                        <td class="actions">
                            <a href="alterar.php?excluir_ponto=<?php echo $registro['res_codigo']; ?>" onclick="return confirm('Tem certeza que deseja excluir este registro de ponto?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Botão Voltar -->
        <a href="index.php" class="back-button">Voltar para a Página Inicial</a>

        <footer>
            <p>&copy; 2024 Sistema de Controle de Ponto</p>
        </footer>
    </div>
</body>
</html>
