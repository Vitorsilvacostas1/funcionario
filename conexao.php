<?php
$servername = "localhost";
$username = "root"; // ou o usuário que você estiver utilizando
$password = ""; // ou a senha configurada no seu banco de dados
$dbname = "funcionario"; // nome do seu banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
