<html>
<head>
    <meta name="robots" content="noindex">
    <!-- Outras tags e metadados do cabeçalho -->
</head>
</html>


<?php
// Arquivo: consulta_destaque.php

// Conexão com o banco de dados
// Aqui você deve substituir as informações de conexão pelas suas informações reais
$servername = "localhost";
$username = "u529068110_down";
$password = "@Erick91492832";
$dbname = "u529068110_down";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$id = $_POST['id']; // Supondo que o ID seja passado via parâmetro POST

$sql = "SELECT destaque FROM tabela_musicas WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentDestaque = $row['destaque'];

    // Alternar o valor do destaque
    $newDestaque = $currentDestaque == 1 ? 0 : 1;

    // Atualizar o destaque no banco de dados
    $updateSql = "UPDATE tabela_musicas SET destaque = $newDestaque WHERE id = $id";
    if ($conn->query($updateSql) === TRUE) {
        header('Content-Type: application/json');
        echo json_encode(['destaque' => $newDestaque]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erro ao atualizar o destaque no banco de dados']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID não encontrado']);
}

$conn->close();
?>
