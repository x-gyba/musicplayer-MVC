<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../config/database.php';

use Config\Database;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    $uploadDir = '../music/';
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $message = 'Erro no upload do arquivo.';
    } elseif ($arquivo['size'] > $maxSize) {
        $message = 'O arquivo excede o tamanho máximo de 10MB.';
    } elseif ($extensao !== 'mp3') {
        $message = 'Somente arquivos MP3 são permitidos.';
    } else {
        $nomeFinal = uniqid('musica_', true) . '.mp3';
        $caminhoRelativo = 'music/' . $nomeFinal;
        $caminhoCompleto = $uploadDir . $nomeFinal;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            try {
                $db = new Database();
                $conn = $db->getConnection();

                $stmt = $conn->prepare("INSERT INTO musicas (caminho_arquivo) VALUES (:caminho)");
                $stmt->bindParam(':caminho', $caminhoRelativo);
                $stmt->execute();

                $message = 'Arquivo enviado e registrado com sucesso!';
            } catch (PDOException $e) {
                $message = 'Erro ao salvar no banco: ' . $e->getMessage();
            }
        } else {
            $message = 'Erro ao mover o arquivo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Upload de Música</title>
</head>
<body>
    <!-- Botão de logout -->
    <form action="logout.php" method="post" style="text-align: right; margin-bottom: 1rem;">
        <button type="submit">Logout</button>
    </form>

    <h2>Enviar Música</h2>

    <form method="post" enctype="multipart/form-data">
        <label for="arquivo">Selecione um arquivo MP3 (máx. 10MB):</label><br>
        <input type="file" name="arquivo" id="arquivo" accept=".mp3" required><br><br>

        <button type="submit">Enviar</button>
    </form>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</body>
</html>
