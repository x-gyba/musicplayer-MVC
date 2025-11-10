<?php
/* Controlador de Autenticação */

session_start();

// Configuração de erros para desenvolvimento (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers para JSON
header('Content-Type: application/json; charset=utf-8');

// Inclui dependências
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->getConnection();
            $this->userModel = new User($this->db);
            
            error_log("✅ AuthController inicializado");
        } catch (Exception $e) {
            error_log("❌ ERRO na inicialização: " . $e->getMessage());
            $this->sendError("Erro de conexão com banco de dados", 500);
            exit;
        }
    }

    /* Processa requisição de login */
    public function login() {
        error_log("==========================================");
        error_log("🔐 INÍCIO DO PROCESSO DE LOGIN");
        error_log("==========================================");
        
        try {
            // 1. Validação do método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST.');
            }

            // 2. Captura dados do POST
            $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
            $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

            error_log("📋 Dados recebidos:");
            error_log("   - Usuário: " . $usuario);
            error_log("   - Senha: " . (empty($senha) ? 'VAZIA' : '[' . strlen($senha) . ' caracteres]'));
            error_log("   - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'desconhecido'));

            // 3. Validação de campos vazios
            if (empty($usuario) || empty($senha)) {
                error_log("❌ Campos vazios detectados");
                throw new Exception('Preencha usuário e senha');
            }

            // 4. Verificar bloqueio por tentativas
            if ($this->userModel->isBlocked($usuario)) {
                error_log("🚫 Usuário bloqueado: " . $usuario);
                throw new Exception('Muitas tentativas falhas. Aguarde 15 minutos.');
            }

            // 5. Buscar usuário no banco
            error_log("🔍 Buscando usuário no banco...");
            $user = $this->userModel->findByUsername($usuario);

            if (!$user) {
                error_log("❌ Usuário não encontrado: " . $usuario);
                
                // Registra tentativa falha
                $this->userModel->logAttempt($usuario, false);
                
                throw new Exception('Usuário ou senha incorretos');
            }

            error_log("✅ Usuário encontrado:");
            error_log("   - ID: " . $user['id']);
            error_log("   - Nome: " . $user['usuario']);
            error_log("   - Email: " . $user['email']);
            error_log("   - Status: " . $user['status']);
            error_log("   - Hash senha (10 primeiros chars): " . substr($user['senha'], 0, 10) . '...');

            // 6. Verificar senha
            error_log("🔐 Verificando senha...");
            error_log("   - Senha digitada (length): " . strlen($senha));
            error_log("   - Hash no banco (length): " . strlen($user['senha']));
            error_log("   - Hash começa com $2y$: " . (substr($user['senha'], 0, 4) === '$2y$' ? 'SIM' : 'NÃO'));

            $senhaValida = password_verify($senha, $user['senha']);
            
            error_log("   - Resultado password_verify(): " . ($senhaValida ? 'TRUE ✅' : 'FALSE ❌'));

            if (!$senhaValida) {
                error_log("❌ SENHA INVÁLIDA");
                
                // Registra tentativa falha
                $this->userModel->logAttempt($usuario, false);
                
                throw new Exception('Usuário ou senha incorretos');
            }

            // 7. Login bem-sucedido!
            error_log("✅ SENHA VÁLIDA - Login autorizado!");

            // Registra tentativa bem-sucedida
            $this->userModel->logAttempt($usuario, true);

            // Atualiza último login
            $this->userModel->updateLastLogin($user['id']);

            // Cria sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['usuario'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();

            error_log("✅ Sessão criada:");
            error_log("   - user_id: " . $_SESSION['user_id']);
            error_log("   - username: " . $_SESSION['username']);

            // Resposta de sucesso
            $this->sendSuccess('Login realizado com sucesso!', [
                'redirect' => 'views/upload.php',
                'user' => [
                    'id' => $user['id'],
                    'usuario' => $user['usuario'],
                    'email' => $user['email']
                ]
            ]);

        } catch (Exception $e) {
            error_log("❌ ERRO no login: " . $e->getMessage());
            error_log("==========================================");
            
            $this->sendError($e->getMessage());
        }
    }

    /* Processa logout */
    public function logout() {
        error_log("👋 Logout do usuário: " . ($_SESSION['username'] ?? 'desconhecido'));
        
        session_destroy();
        
        $this->sendSuccess('Logout realizado', [
            'redirect' => '../index.php'
        ]);
    }

    /* Envia resposta de sucesso */
    private function sendSuccess($message, $data = []) {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        error_log("✅ Resposta de sucesso: " . json_encode($response));
        echo json_encode($response);
        exit;
    }

    /* Envia resposta de erro */
    private function sendError($message, $code = 400) {
        http_response_code($code);
        
        $response = [
            'success' => false,
            'message' => $message
        ];

        error_log("❌ Resposta de erro: " . json_encode($response));
        echo json_encode($response);
        exit;
    }
}

// ==========================================
// EXECUÇÃO DO CONTROLADOR
// ==========================================

try {
    $controller = new AuthController();
    
    // Verifica ação solicitada
    $action = $_GET['auth_action'] ?? $_POST['auth_action'] ?? 'login';
    
    error_log("🎯 Ação solicitada: " . $action);
    
    switch ($action) {
        case 'login':
            $controller->login();
            break;
            
        case 'logout':
            $controller->logout();
            break;
            
        default:
            throw new Exception('Ação inválida');
    }
    
} catch (Exception $e) {
    error_log("💥 ERRO FATAL: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor: ' . $e->getMessage()
    ]);
}