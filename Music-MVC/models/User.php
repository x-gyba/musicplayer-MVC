<?php
/**
 * Model User - Gerencia operações de usuários
 * 
 * @package Music-MVC
 * @author InfoGyba
 * @version 2.0
 */

class User {
    private $conn;
    private $table_users = "usuarios";
    private $table_logs = "tentativas_login";

    // Propriedades públicas
    public $id;
    public $usuario;
    public $senha;
    public $email;
    public $status;
    public $last_login;

    /**
     * Construtor
     * @param PDO $db Conexão com banco de dados
     */
    public function __construct($db) {
        $this->conn = $db;
        error_log("✅ User Model inicializado");
    }

    /**
     * Busca usuário por nome de usuário ou email
     * 
     * @param string $username Nome do usuário ou email
     * @return array|false Dados do usuário ou false se não encontrado
     */
    public function findByUsername($username) {
        try {
            error_log("🔍 Buscando usuário: " . $username);
            
            $query = "SELECT id, usuario, senha, email, status, last_login
                      FROM " . $this->table_users . "
                      WHERE (usuario = :username OR email = :username)
                      AND status = 'active'
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            
            // Limpa o username (remove espaços)
            $cleanUsername = trim($username);
            $stmt->bindParam(':username', $cleanUsername, PDO::PARAM_STR);
            
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                error_log("✅ Usuário encontrado:");
                error_log("   - ID: " . $user['id']);
                error_log("   - Nome: " . $user['usuario']);
                error_log("   - Email: " . $user['email']);
                error_log("   - Status: " . $user['status']);
                error_log("   - Hash (10 chars): " . substr($user['senha'], 0, 10) . "...");
            } else {
                error_log("❌ Usuário não encontrado: " . $username);
            }

            return $user;

        } catch (PDOException $e) {
            error_log("💥 ERRO em findByUsername: " . $e->getMessage());
            error_log("   - Query: " . $query);
            error_log("   - Username: " . $username);
            return false;
        }
    }

    /**
     * Atualiza o timestamp do último login
     * 
     * @param int $userId ID do usuário
     * @return bool Sucesso da operação
     */
    public function updateLastLogin($userId) {
        try {
            error_log("📅 Atualizando último login do usuário ID: " . $userId);
            
            $query = "UPDATE " . $this->table_users . "
                      SET last_login = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            $result = $stmt->execute();
            
            if ($result) {
                error_log("✅ Último login atualizado com sucesso");
            } else {
                error_log("⚠️ Falha ao atualizar último login");
            }

            return $result;

        } catch (PDOException $e) {
            error_log("💥 ERRO em updateLastLogin: " . $e->getMessage());
            error_log("   - User ID: " . $userId);
            return false;
        }
    }

    /**
     * Registra tentativa de login (sucesso ou falha)
     * 
     * @param string $username Nome do usuário
     * @param bool $success Se a tentativa foi bem-sucedida
     * @return bool Sucesso do registro
     */
    public function logAttempt($username, $success) {
        try {
            $successInt = $success ? 1 : 0;
            $status = $success ? "✅ SUCESSO" : "❌ FALHA";
            
            error_log("📝 Registrando tentativa de login:");
            error_log("   - Usuário: " . $username);
            error_log("   - Status: " . $status);
            
            $query = "INSERT INTO " . $this->table_logs . "
                      (usuario, ip_address, success, attempted_at)
                      VALUES (:usuario, :ip_address, :success, CURRENT_TIMESTAMP)";

            $stmt = $this->conn->prepare($query);

            // Captura IP do cliente
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            
            // Se estiver atrás de proxy, tenta pegar o IP real
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
            }

            error_log("   - IP: " . $ipAddress);

            $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
            $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam(':success', $successInt, PDO::PARAM_INT);

            $result = $stmt->execute();
            
            if ($result) {
                error_log("✅ Tentativa registrada com sucesso");
            } else {
                error_log("⚠️ Falha ao registrar tentativa");
            }

            return $result;

        } catch (PDOException $e) {
            error_log("💥 ERRO em logAttempt: " . $e->getMessage());
            error_log("   - Username: " . $username);
            error_log("   - Success: " . ($success ? 'true' : 'false'));
            return false;
        }
    }

    /**
     * Verifica se usuário está bloqueado por excesso de tentativas
     * 
     * @param string $username Nome do usuário
     * @param int $minutes Janela de tempo em minutos (padrão: 15)
     * @param int $maxAttempts Máximo de tentativas permitidas (padrão: 5)
     * @return bool True se bloqueado, false caso contrário
     */
    public function isBlocked($username, $minutes = 15, $maxAttempts = 5) {
        try {
            error_log("🚦 Verificando bloqueio:");
            error_log("   - Usuário: " . $username);
            error_log("   - Janela: últimos " . $minutes . " minutos");
            error_log("   - Limite: " . $maxAttempts . " tentativas");
            
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
            $totalFalhas = (int)$result['total'];
            
            $bloqueado = ($totalFalhas >= $maxAttempts);

            error_log("   - Tentativas falhas: " . $totalFalhas);
            error_log("   - Resultado: " . ($bloqueado ? "🚫 BLOQUEADO" : "✅ LIBERADO"));

            return $bloqueado;

        } catch (PDOException $e) {
            error_log("💥 ERRO em isBlocked: " . $e->getMessage());
            error_log("   - Username: " . $username);
            
            // Em caso de erro, não bloqueia por segurança
            return false;
        }
    }

    /**
     * Limpa tentativas antigas de login (manutenção)
     * 
     * @param int $days Dias de histórico para manter (padrão: 30)
     * @return bool Sucesso da operação
     */
    public function cleanOldAttempts($days = 30) {
        try {
            error_log("🧹 Limpando tentativas antigas (> " . $days . " dias)");
            
            $query = "DELETE FROM " . $this->table_logs . "
                      WHERE attempted_at < DATE_SUB(NOW(), INTERVAL :days DAY)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':days', $days, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            $deleted = $stmt->rowCount();
            
            error_log("✅ Registros deletados: " . $deleted);

            return $result;

        } catch (PDOException $e) {
            error_log("💥 ERRO em cleanOldAttempts: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém estatísticas de tentativas de login
     * 
     * @param string $username Nome do usuário (opcional)
     * @param int $hours Últimas N horas (padrão: 24)
     * @return array Estatísticas
     */
    public function getLoginStats($username = null, $hours = 24) {
        try {
            $query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as sucessos,
                        SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as falhas
                      FROM " . $this->table_logs . "
                      WHERE attempted_at > DATE_SUB(NOW(), INTERVAL :hours HOUR)";

            if ($username) {
                $query .= " AND usuario = :usuario";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':hours', $hours, PDO::PARAM_INT);
            
            if ($username) {
                $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
            }

            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("📊 Estatísticas de login (últimas " . $hours . "h):");
            error_log("   - Total: " . $stats['total']);
            error_log("   - Sucessos: " . $stats['sucessos']);
            error_log("   - Falhas: " . $stats['falhas']);

            return $stats;

        } catch (PDOException $e) {
            error_log("💥 ERRO em getLoginStats: " . $e->getMessage());
            return [
                'total' => 0,
                'sucessos' => 0,
                'falhas' => 0
            ];
        }
    }
}