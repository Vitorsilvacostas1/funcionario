<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $res_codigo = $_GET['id'];
    $sql = "DELETE FROM tbl_registro WHERE res_codigo = '$res_codigo'";

    if ($conn->query($sql) === TRUE) {
        echo "Registro excluído com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>
