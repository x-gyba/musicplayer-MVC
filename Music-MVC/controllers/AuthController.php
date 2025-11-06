<?php
/* AuthController.php */
 /* Gerencia a lógica de autenticação (Login e Logout).*/

/* Inicia a sessão se ainda não estiver iniciada */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/* Inclusão das dependências */
/* O caminho de inclusão é ajustado para a estrutura 'Music-MVC' */
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php'; 
/* Classe Controller */
class AuthController {
    private $userModel;

    public function __construct() {
/* Assume que a classe User() lida com a conexão de banco de dados internamente. */
        $this->userModel = new User(); 
    }

    /* Lida com a requisição de login. Espera um POST (usado com AJAX). */
    /* Retorna uma resposta JSON. */
    public function login() {
        // Configura o cabeçalho para JSON
        header('Content-Type: application/json');

        /* Validação básica da requisição */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['username']) || !isset($_POST['password'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
            exit;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        /* Chama o método de autenticação do modelo */
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            /* Autenticação bem-sucedida: Cria variáveis de sessão*/
            $_SESSION['logged_in'] = true; 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Ex: 'admin', 'user'

            /* Retorna JSON de sucesso. O JS fará o redirecionamento. */
            /* O caminho 'views/upload.php' é um exemplo. */
            echo json_encode(['success' => true, 'redirect' => '../views/upload.php']); 
            exit;
        } else {
            /* Falha na autenticação */
            http_response_code(401); /* Unauthorized */
            echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos.']);
            exit;
        }
    }

    /* Lida com o processo de logout. /*
    /* Limpa a sessão e redireciona para a página inicial.*/
      public function logout() {
        $_SESSION = array(); // Limpa todas as variáveis de sessão
        session_destroy();   // Destrói a sessão
        header('Location: ../index.php'); // Redireciona
        exit;
    }
}

/* Verifica se há uma ação solicitada na URL */
if (isset($_GET['action'])) {
    /* Instancia o controlador UMA VEZ ) */
    $controller = new AuthController(); 
    $action = $_GET['action'];

    if ($action === 'login') {
   /* Executa o método de login */
        $controller->login();
    } elseif ($action === 'logout') {
    /* Executa o método de logout */
        $controller->logout();
    } 
    
}

?> 