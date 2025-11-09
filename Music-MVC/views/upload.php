<?php
// views/upload.php

// Inicia a sessão para acessar as variáveis de login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Se não estiver logado, redireciona para a página principal ou login
    header('Location: ../index.html'); // Ajuste conforme a localização da sua página principal
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Upload - Sola Gratia</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            text-align: center;
        }
        .panel {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #c0392b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="panel">
        <h2>Bem-vindo, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Você acessou o painel de upload com sucesso.</p>
        <p>Aqui você poderá gerenciar suas músicas.</p>
        <a href="logout.php" class="btn-logout">Sair (Logout)</a>
    </div>
</body>
</html>