<?php
/* config/database.php */
/* classe de conexão com o banco de dados usando PDO */

class Database {
    // Configurações do banco de dados
    private $host = "localhost";
    private $db_name = "music";
    private $username = "root";
    private $password = "xp8x11";
    private $charset = "utf8mb4";

    public $conn;

    /* Obtém a conexão com o banco de dados */
    /* @return PDO|null Retorna a conexão PDO ou null em caso de falha  */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch(PDOException $e) {
            // Log do erro (não mostrar detalhes ao usuário)
            error_log("ERRO DE CONEXÃO: " . $e->getMessage());
            error_log("DSN: mysql:host=" . $this->host . ";dbname=" . $this->db_name);

            /* Retorna null em caso de erro */
            $this->conn = null;
        }

        return $this->conn;
    }

    /* Testa a conexão com o banco de dados */
    /* @return bool True se a conexão for bem-sucedida */
      public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn === null) {
                return false;
            }

            // Testa uma query simples
            $stmt = $conn->query("SELECT 1");
            return $stmt !== false;

        } catch(Exception $e) {
            error_log("ERRO NO TESTE DE CONEXÃO: " . $e->getMessage());
            return false;
        }
    }
}
?>
