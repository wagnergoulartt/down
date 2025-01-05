<html>
<head>
    <meta name="robots" content="noindex">
    <!-- Outras tags e metadados do cabeçalho -->
</head>
</html>


<?php
// Verificar se foi fornecido um ID válido
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "ID inválido.";
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "u529068110_down";
$password = "@Erick91492832";
$dbname = "u529068110_down";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Obter o ID da música
$id = $_GET["id"];

// Consulta para obter as informações da música
$sql = "SELECT nome_musica, url, downloads FROM musicas WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $nomeMusica = $row["nome_musica"];
    $url = $row["url"];

    // Incrementar o contador de downloads
    $downloads = $row["downloads"] + 1;
    $updateSql = "UPDATE musicas SET downloads = $downloads WHERE id = $id";
    $conn->query($updateSql);

    // Definir os headers para o download
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $nomeMusica . ".mp3");

    // Ler o arquivo MP3 e enviá-lo para o cliente
    readfile($url);
    exit();
} else {
    echo "Música não encontrada.";
}

$conn->close();
?>
