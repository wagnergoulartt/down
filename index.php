<html>
<head>
    <meta name="robots" content="noindex">
</head>
</html>

<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "u529068110_down";
$password = "@Erick91492832";
$dbname = "u529068110_down";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
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
$searchSql = !empty($search) ? "WHERE nome_musica LIKE '%$search%' OR nome_dj LIKE '%$search%'" : "WHERE 1=1";

// Consulta para obter as músicas com limite, offset e busca
$sql = "SELECT id, nome_musica, nome_dj, downloads, destaque 
        FROM musicas $searchSql 
        ORDER BY destaque DESC, id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Download de MP3</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <meta http-equiv="Content-Language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 1px;
            border: none;
            vertical-align: middle;
        }

        .music {
            border-top: none;
        }

        .texto {
            margin-bottom: 10px;
            color: #343a40;
        }

        .music-name {
            margin-bottom: -0.5em;
            margin-top: 1px;
        }

        .center {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .texto-download {
            display: flex;
            align-items: center;
            margin-top: 10px;
            font-size: 10pt;
        }

        .texto-download p {
            margin-left: 5px;
        }

        .pagination {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            justify-content: center;
        }

        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #eee;
            text-decoration: none;
            color: #333;
        }

        .pagination a.active {
            background-color: #193461;
            color: #fff;
        }

        .dj-name {
            margin-bottom: 1px;
            margin-top: 5px;
            font-size: 10pt;
        }

        .download-icon {
            margin-bottom: 10px;
            font-size: 20pt;
            color: #193461;
        }

        .download-icon:hover {
            color: #495057;
        }

        .textoo {
            padding-right: 10px;
        }

        .btn-color {
            background-color: #193461;
            color: #FFFFFF;
        }

        .btn-color:hover {
            background-color: #0000FF;
            color: #FFFFFF;
        }

        .border-color {
            border-color: #193461;
        }

        .border-color:focus {
            outline: none;
            box-shadow: 0 0 5px #193461;
        }

        .music-destacada {
            background-color: #e8f0fe !important;
            border-left: 3px solid #193461 !important;
        }

        .table-bordered>:not(caption)>*>* {
            border-width: 0;
        }

        .music.music-destacada {
            background-color: #e8f0fe !important;
            border-left: 3px solid #193461 !important;
        }

        .table>:not(caption)>*>* {
            background-color: transparent;
        }
    </style>
</head>
<body>
    <div class="container">
        </br>
        <div class="input-group mb-3">
            <input type="text" id="searchInput" class="form-control border-color" 
                   placeholder="Pesquisar música" onkeyup="searchMusic(this.value)">
        </div>

        <table class="table table-bordered">
            <tbody id="musicList">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $destacadaClass = ($row["destaque"] == 1) ? 'music-destacada' : '';
                        echo "<tr class='music {$destacadaClass}'>";
                        echo "<td>";
                        echo "<p class='texto music-name'>" . $row["nome_musica"] . "</p>";
                        echo "<p class='texto dj-name'>" . $row["nome_dj"] . "</p>";
                        echo "</td>";
                        echo "<td class='center'>";
                        echo "<div class='texto-download'>";
                        echo "<p class='textoo'>" . $row["downloads"] . "</p>";
                        echo "<a href='download.php?id=" . $row["id"] . "'><i class='fas fa-download download-icon'></i></a>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'><center>Nenhuma música encontrada.</center></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
    let searchTimeout = null;

    function searchMusic(query) {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            fetch(`?search=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMusicList = doc.querySelector('#musicList');
                    
                    if (newMusicList) {
                        document.querySelector('#musicList').innerHTML = newMusicList.innerHTML;
                    }
                    
                    sendHeightToParent();
                })
                .catch(error => console.error('Erro:', error));
        }, 300);
    }

    function sendHeightToParent() {
        var height = document.documentElement.scrollHeight;
        parent.postMessage({ iframeHeight: height }, '*');
    }

    sendHeightToParent();
    </script>

</body>
</html>

<?php
$conn->close();
?>
