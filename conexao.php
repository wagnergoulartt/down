<html>
<head>
    <meta name="robots" content="noindex">
    <!-- Outras tags e metadados do cabeçalho -->
</head>
</html>

<?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "u529068110_down";
$password = "@Erick91492832";
$dbname = "u529068110_down";

// Criar a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se ocorreu algum erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Função para executar uma consulta SQL
function executarConsulta($sql) {
    global $conn;
    $result = $conn->query($sql);
    return $result;
}

// Função para escapar os valores antes de usar em uma consulta SQL
function escaparValor($valor) {
    global $conn;
    return $conn->real_escape_string($valor);
}
?>
