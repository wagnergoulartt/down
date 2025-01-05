<html>
<head>
    <meta name="robots" content="noindex">
    <!-- Outras tags e metadados do cabeçalho -->
</head>
</html>


<?php
// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar os dados de login
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Estabelecer a conexão com o banco de dados (substitua com suas próprias informações de conexão)
    $servername = "localhost";
    $username = "u529068110_down";
    $password = "@Erick91492832";
    $dbname = "u529068110_down";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Verificar o email e senha no banco de dados
    $sql = "SELECT * FROM users WHERE email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);

    // Verificar se a consulta retornou algum resultado
    if ($result->num_rows === 1) {
        // Login bem-sucedido
        $usuario = $result->fetch_assoc();
        // Definir a variável de sessão para indicar que o usuário está logado
        session_start();
        $_SESSION['logged_in'] = true;
        // Redirecionar para a página admin.php
        header('Location: admin.php');
        exit;
    } else {
        // Mostrar mensagem de erro se o login falhar
        $erro = "Login inválido. Tente novamente.";
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #cc0000;
            margin-bottom: 10px;
            text-align: center;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if (isset($erro)) { ?>
            <p><?php echo $erro; ?></p>
        <?php } ?>

        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required><br><br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
