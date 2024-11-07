<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $res_codigo = $_GET['id'];
    $registro = $conn->query("SELECT * FROM tbl_registro WHERE res_codigo = $res_codigo")->fetch_assoc();

    if (!$registro) {
        echo "<div class='message'>Registro não encontrado!</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $sql = "UPDATE tbl_registro SET res_data = '$data', res_hora = '$hora' WHERE res_codigo = $res_codigo";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message'>Registro atualizado com sucesso!</div>";
        header('Location: registro_ponto.php');
        exit;
    } else {
        echo "<div class='message'>Erro ao atualizar: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro de Ponto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Registro de Ponto</h1>

        <form method="POST">
            <label for="data">Data:</label>
            <input type="date" name="data" id="data" value="<?php echo $registro['res_data']; ?>" required><br>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" id="hora" value="<?php echo $registro['res_hora']; ?>" required><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <footer>
            <p>&copy; 2024 Sistema de Controle de Ponto</p>
        </footer>
    </div>
</body>
</html>
