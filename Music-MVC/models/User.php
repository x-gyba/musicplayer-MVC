<?php
/* Model para interagir com a tabela de usuários */

class User {
    private $conn;
    private $table_users = "usuarios";
    private $table_logs = "tentativas_login";

    public $id;
    public $usuario;
    public $senha;
    public $email;
    public $status;
    public $last_login;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* Busca usuário pelo nome de usuário */
    /* @param string $username Nome do usuário */
    /* @return array|false Dados do usuário ou false */
    public function findByUsername($username) {
        try {
            $query = "SELECT id, usuario, senha, email, status, last_login
                      FROM " . $this->table_users . "
                      WHERE (usuario = :username OR email = :username)
                      AND status = 'active'
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                error_log("Usuário encontrado: " . $user['usuario']);
            } else {
                error_log("Usuário não encontrado: " . $username);
            }

            return $user;

        } catch (PDOException $e) {
            error_log("ERRO findByUsername: " . $e->getMessage());
            return false;
        }
    }

    /* Atualiza o último login do usuário */
     /* @param int $userId ID do usuário */
     /* @return bool Sucesso da operação */
       public function updateLastLogin($userId) {
        try {
            $query = "UPDATE " . $this->table_users . "
                      SET last_login = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("ERRO updateLastLogin: " . $e->getMessage());
            return false;
        }
    }

    
     /* Registra tentativa de login */
     /* @param string $username Nome do usuário */
     /* @param bool $success Se foi bem-sucedido */
     /* @return bool Sucesso do registro */
    
    public function logAttempt($username, $success) {
        try {
            $query = "INSERT INTO " . $this->table_logs . "
                      (usuario, ip_address, success, attempted_at)
                      VALUES (:usuario, :ip_address, :success, CURRENT_TIMESTAMP)";

            $stmt = $this->conn->prepare($query);

            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $successInt = $success ? 1 : 0;

            $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
            $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam(':success', $successInt, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("ERRO logAttempt: " . $e->getMessage());
            return false;
        }
    }

    /* Verifica se há muitas tentativas falhadas recentes */
    /* @param string $username Nome do usuário */
    /* @param int $minutes Minutos para verificar */
    /* @param int $maxAttempts Máximo de tentativas permitidas */
    /* @return bool True se bloqueado */
    
    public function isBlocked($username, $minutes = 15, $maxAttempts = 5) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_logs . "
                      WHERE usuario = :usuario
                      AND success = 0
                      AND attempted_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
            $stmt->bindParam(':minutes', $minutes, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['total'] >= $maxAttempts);

        } catch (PDOException $e) {
            error_log("ERRO isBlocked: " . $e->getMessage());
            return false;
        }
    }
}
?>

