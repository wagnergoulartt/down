<html>
<head>
    <meta name="robots" content="noindex">
    <!-- Outras tags e metadados do cabeçalho -->
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
$sql = "SELECT id, nome_musica, nome_dj, downloads 
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
            
            
        }
        
        .textoo {
            padding-right: 10px;
        }
        
        .download-icon {
      color: #193461; /* Cor padrão do ícone */
    }
    
    .download-icon:hover {
      color: #495057; /* Cor ao passar o mouse */
    }
    .btn-color {
  background-color: #193461; /* Substitua #FF0000 pela cor desejada */
  color: #FFFFFF; /* Substitua #FFFFFF pela cor do texto desejada */
}
.btn-color:hover {
  background-color: #0000FF; /* Substitua #0000FF pela nova cor de fundo ao passar o mouse */
  color: #FFFFFF; /* Substitua #FFFFFF pela nova cor do texto ao passar o mouse */
}
.border-color {
  border-color: #193461; /* Substitua #FF0000 pela cor desejada para a borda */
}
.border-color:focus {
  outline: none; /* Remove a sombra padrão ao selecionar o campo */
  box-shadow: 0 0 5px #193461; /* Substitua #0000FF pela nova cor da sombra ao selecionar o campo */
}
   
    </style>
</head>
<body>
    </div>
    <div class="container">
        </br>
<form action="" method="get">
  <div class="input-group mb-3">
    <input type="text" name="search" class="form-control border-color" placeholder="Pesquisar música" value="<?php echo $search; ?>">
    <button type="submit" class="btn btn-secondary btn-color">Buscar</button>
  </div>
</form>

        <table class="table table-bordered">
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Exibir as músicas disponíveis
                    $counter = 0;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='music'>";
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

                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='2'><center>Nenhuma música encontrada.</center> <center><button onclick='voltarListagem()'>VOLTAR AO INÍCIO</button></center></td></tr>";

// Em algum lugar abaixo no seu código PHP, você pode adicionar o seguinte script JavaScript:
echo "<script>
function voltarListagem() {
  window.location.href = 'https://gruposocializando.com.br/down/';
}
</script>";
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<script>
    // Envie a altura do conteúdo para a página pai
function sendHeightToParent() {
    var height = document.documentElement.scrollHeight;
    parent.postMessage({ iframeHeight: height }, '*');
}
// Chame a função sempre que houver alteração no conteúdo da página
// Por exemplo, após um evento de carregamento ou uma alteração de tamanho
sendHeightToParent();
    
</script>

<?php
$conn->close();
?>
