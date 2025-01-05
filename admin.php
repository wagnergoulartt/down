<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirecionar de volta para a página de login
    header('Location: login.php');
    exit;
}
?>

<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "u529068110_down";
$password = "@Erick91492832";
$dbname = "u529068110_down";



$conn = new mysqli($servername, $username, $password, $dbname);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
// Função para adicionar música
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['edit_id'])) {
    $nomeMusica = $_POST["nome_musica"];
    $nomeDJ = $_POST["nome_dj"];
    $url = $_POST["url"];
    $downloads = $_POST["downloads"];
    $destaque = isset($_POST["destaque"]) && $_POST["destaque"] === "1" ? 1 : 0;

    $sqlInsert = "INSERT INTO musicas (nome_musica, nome_dj, url, downloads, destaque) VALUES ('$nomeMusica', '$nomeDJ', '$url', $downloads, $destaque)";
   if ($conn->query($sqlInsert) === TRUE) {
    echo "Música adicionada com sucesso!";
} else {
    echo "Erro ao adicionar música: " . mysqli_error($conn);
}
}

// Função para excluir música
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idMusica = $_GET['id'];

    $sqlDelete = "DELETE FROM musicas WHERE id = $idMusica";
    if ($conn->query($sqlDelete) === TRUE) {
        echo "Música excluída com sucesso!";
    } else {
        echo "Erro ao excluir música: " . $conn->error;
    }
}

// Função para editar música
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $musicId = $_POST['edit_id'];
    $banda = $_POST['nome_musica'];
    $musica = $_POST['nome_dj'];
    $url = $_POST['url'];
    $downloads = $_POST['downloads'];
    $destaque = isset($_POST["destaque"]) && $_POST["destaque"] == "1" ? 1 : 0;

$sqlUpdate = "UPDATE musicas SET 
              nome_musica = ?,
              nome_dj = ?,
              url = ?,
              downloads = ?,
              destaque = ?
              WHERE id = ?";

// Preparar a instrução
$stmt = $conn->prepare($sqlUpdate);

// Vincular os parâmetros
$stmt->bind_param("sssiis", $banda, $musica, $url, $downloads, $destaque, $musicId);

// Executar a instrução
if ($stmt->execute()) {
    echo "Música atualizada com sucesso.";
} else {
    echo "Erro ao atualizar a música: " . $stmt->error;
}

// Fechar a instrução
$stmt->close();
    }




// Parâmetros da paginação
$limit = 30; // Quantidade de resultados por página

// Obtém o número total de músicas
$sqlCount = "SELECT COUNT(*) AS total FROM musicas";
$countResult = $conn->query($sqlCount);
$totalRecords = $countResult->fetch_assoc()['total'];

// Calcula o número total de páginas
$totalPages = ceil($totalRecords / $limit);

// Obtém o número da página atual
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = max(1, min($totalPages, intval($page))); // Garante que o número da página esteja dentro dos limites

// Calcula o offset para a consulta
$offset = ($page - 1) * $limit;

// Obtém a consulta de busca (se houver)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchSql = !empty($search) ? "WHERE nome_musica LIKE '%$search%' OR nome_dj LIKE '%$search%'" : "";

// Consulta para obter as músicas com limite, offset e busca
$sql = "SELECT id, nome_musica, nome_dj, url, downloads, destaque 
        FROM musicas 
        $searchSql 
        ORDER BY destaque DESC, id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Array para armazenar as músicas
$musicas = [];

if ($result->num_rows > 0) {
    // Preenche o array de músicas com os resultados da consulta
    while ($row = $result->fetch_assoc()) {
        $musicas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Download de MP3</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="down.css">
    <script src="down.js"></script>
    <meta name="robots" content="noindex">
    <meta http-equiv="Content-Language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
      <style>
        .sair-button {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #193461;
            float: right;
            margin-right: 10px;
        }
        .sair-button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="topo" style="visibility: hidden;"></div>
    <div class="container">
        </br>
        <a href="sair.php" class="sair-button"><i class="fas fa-sign-out-alt"></i>Sair</a>
        </br>
        </br>
        <form id="addForm" action="" method="post">
    <div class="mb-3">
        <label for="nome_musica" class="form-label">Cantor / Banda:</label>
        <input type="text" name="nome_musica" id="nome_musica" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="nome_dj" class="form-label">Música:</label>
        <input type="text" name="nome_dj" id="nome_dj" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="url" class="form-label">(URL) Música:</label>
        <input type="text" name="url" id="url" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="downloads" class="form-label">Download:</label>
        <input type="number" name="downloads" id="downloads" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="destaque" class="form-check-label">Destaque:</label>
        <div class="form-check">
            <input type="radio" name="destaque" id="destaque_sim" class="form-check-input" value="1">
            <label for="destaque_sim" class="form-check-label">Sim</label>
        </div>
        <div class="form-check">
            <input type="radio" name="destaque" id="destaque_nao" class="form-check-input" value="0" checked>
            <label for="destaque_nao" class="form-check-label">Não</label>
        </div>
    </div>
    <input type="hidden" name="edit_id" id="edit_id">
    <input type="hidden" name="action" id="action" value="add">
    <button type="submit" class="btn btn-secondary">Adicionar/Editar Música</button>
</form>

<input type="hidden" name="action" value="add">
        <table class="table table-bordered">
            <tbody>
                
               <?php
if (!empty($musicas)) {
    foreach ($musicas as $row) {
        echo "<tr>";
        echo "<td>";
        echo "<p class='music-name'>" . $row["nome_musica"] . "</p>";
        echo "<p class='dj-name'>" . $row["nome_dj"] . "</p>";
        echo "</td>";
        echo "<td>" . $row["downloads"] . "</td>";
        
        // Verifica se a música está em destaque
        if ($row["destaque"] == 1) {
            echo "<td><strong>Sim</strong></td>"; // Destaque em negrito
        } else {
            echo "<td>Não</td>";
        }

        echo "<td><i class='fas fa-edit edit-icon' onclick='editSong(" . $row["id"] . ", \"" . $row["nome_musica"] . "\", \"" . $row["nome_dj"] . "\", \"" . $row["url"] . "\", " . $row["downloads"] . ", " . $row["destaque"] . ")'></i></td>";
        echo "<td><i class='fas fa-trash delete-icon' onclick='deleteSong(" . $row["id"] . ")'></i></td>";
        echo "</tr>";
    }
}
?>
            </tbody>
        </table>

        <?php
        // Exibe links de paginação somente se houver mais de 30 itens
        if ($totalPages > 1) {
            echo "<div class='pagination'>";
            echo "<a href='?page=1'>Primeira</a>";

            for ($i = max(1, $page - 5); $i <= min($page + 5, $totalPages); $i++) {
                if ($i == $page) {
                    echo "<a href='?page=$i' class='active'>$i</a>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }

            echo "<a href='?page=$totalPages'>Última</a>";
            echo "</div>";
        }
        ?>
    </div>
    <script>
        function deleteSong(id) {
            if (confirm("Tem certeza que deseja excluir esta música?")) {
                window.location.href = "?action=delete&id=" + id;
            }
        }

        function editSong(id, nome_musica, nome_dj, url, downloads, destaque) {
    document.getElementById('nome_musica').value = nome_musica;
    document.getElementById('nome_dj').value = nome_dj;
    document.getElementById('url').value = url;
    document.getElementById('downloads').value = downloads;
    document.getElementById('destaque_sim').checked = destaque == 1;
    document.getElementById('destaque_nao').checked = destaque == 0;
    document.getElementById('edit_id').value = id;
    document.getElementById('action').value = "edit";

    var topo = document.getElementById('topo');
    topo.style.visibility = 'visible';
    topo.scrollIntoView({
        behavior: 'smooth'
    });
}
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>