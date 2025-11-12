<?php
require_once '../config/database.php';

use Config\Database;

header('Content-Type: application/json');

session_start();

/* Verifica se é uma requisição de login */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['auth_action']) && $_GET['auth_action'] === 'login') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!$usuario || !$senha) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuário e senha são obrigatórios.'
        ]);
        exit;
    }

    try {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND status = 'active'");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $success = 0; /* Variável para registrar o sucesso da tentativa */

        if ($user && password_verify($senha, $user->senha)) {
            /* Configura a sessão */
            $_SESSION['usuario'] = $user->usuario;
            $_SESSION['id'] = $user->id;

            /* Atualiza a data do último login */
            $update = $conn->prepare("UPDATE usuarios SET last_login = NOW() WHERE id = :id");
            $update->bindParam(':id', $user->id);
            $update->execute();

            $success = 1;

            echo json_encode([
                'success' => true,
                'message' => 'Login realizado com sucesso.',
                'redirect' => 'views/upload.php'
            ]);
        } else {
            /* Credenciais inválidas */
            echo json_encode([
                'success' => false,
                'message' => 'Usuário ou senha inválidos.'
            ]);
        }

        /* Registra a tentativa de login no banco de dados */
        $log = $conn->prepare("INSERT INTO tentativas_login (usuario, ip_address, success) VALUES (:usuario, :ip, :success)");
        $log->bindParam(':usuario', $usuario);
        $log->bindParam(':ip', $ip);
        $log->bindParam(':success', $success);
        $log->execute();
    } catch (PDOException $e) {
        /* Erro de conexão ou PDO */
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao conectar com o banco de dados.'
        ]);
    }

    exit;
}

/* Se não for uma requisição de login válida, retorna erro */
echo json_encode([
    'success' => false,
    'message' => 'Requisição inválida.'
]);
exit;