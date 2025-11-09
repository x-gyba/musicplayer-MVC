<?php
 /* DESCRIÇÃO: Controller para autenticação de usuários */
 
/* Inicia a sessão */
session_start();

/* Habilita exibição de erros para debug (REMOVER EM PRODUÇÃO) */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

/* Log de início */
error_log("========================================");
error_log("AuthController iniciado");
error_log("Método: " . $_SERVER['REQUEST_METHOD']);
error_log("========================================");

/* Headers CORS e JSON */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

/* Resposta padrão */
$response = [
    'success' => false,
    'message' => 'Erro desconhecido'
];

try {
    /* Verifica método HTTP */
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        $response['message'] = 'Método não permitido. Use POST.';
        echo json_encode($response);
        exit;
    }

    /* Verifica ação */
    $action = $_GET['auth_action'] ?? '';
    error_log("Ação solicitada: " . $action);

    if ($action !== 'login') {
        http_response_code(400);
        $response['message'] = 'Ação inválida';
        echo json_encode($response);
        exit;
    }

    /* Inclui dependências */
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/User.php';

    /* Conecta ao banco */
    $database = new Database();
    $db = $database->getConnection();

    if ($db === null) {
        throw new Exception('Erro ao conectar com o banco de dados');
    }

    error_log("Conexão com banco estabelecida");

    /* Captura dados do POST */
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    error_log("Usuário recebido: " . $usuario);
    error_log("Senha recebida: " . (empty($senha) ? 'VAZIA' : 'PREENCHIDA'));

    /* Validação básica */
    if (empty($usuario) || empty($senha)) {
        $response['message'] = 'Por favor, preencha todos os campos.';
        echo json_encode($response);
        exit;
    }

    /* Instancia o model */
    $userModel = new User($db);

    /* Verifica se está bloqueado */
    if ($userModel->isBlocked($usuario)) {
        error_log("Usuário bloqueado: " . $usuario);
        $response['message'] = 'Muitas tentativas falhadas. Aguarde 15 minutos.';
        echo json_encode($response);
        exit;
    }

    /* Busca o usuário */
    $user = $userModel->findByUsername($usuario);

    if (!$user) {
        error_log("Usuário não encontrado: " . $usuario);
        $userModel->logAttempt($usuario, false);
        $response['message'] = 'Usuário ou senha incorretos.';
        echo json_encode($response);
        exit;
    }

    error_log("Usuário encontrado - ID: " . $user['id']);
    error_log("Hash do banco: " . substr($user['senha'], 0, 20) . "...");

    /* Verifica a senha */
    if (!password_verify($senha, $user['senha'])) {
        error_log("Senha incorreta para: " . $usuario);
        $userModel->logAttempt($usuario, false);
        $response['message'] = 'Usuário ou senha incorretos.';
        echo json_encode($response);
        exit;
    }

    error_log("Senha verificada com sucesso!");

    /* Login bem-sucedido */
    $userModel->logAttempt($usuario, true);
    $userModel->updateLastLogin($user['id']);

    /* Cria sessão */
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['login_time'] = time();

    error_log("Sessão criada - User ID: " . $user['id']);

    /* Resposta de sucesso */
    $response = [
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'user_id' => $user['id'],
        'usuario' => $user['usuario'],
        'redirect' => '../views/upload.php'
    ];

    error_log("✅ LOGIN BEM-SUCEDIDO: " . $usuario);
    echo json_encode($response);

} catch (Exception $e) {
    error_log("ERRO FATAL: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    $response['message'] = 'Erro interno do servidor. Verifique os logs.';
    $response['error_details'] = $e->getMessage(); /* REMOVER EM PRODUÇÃO */

    echo json_encode($response);
}
?>