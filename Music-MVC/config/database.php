<?php
use PDO;
use PDOException;

class Database {
/* global */
    private $host = 'localhost';
    private $db_name = 'music_db'; 
    private $username = 'root';
    private $password = ''; 

    public $conn;

    /* Obtém a conexão PDO com o banco de dados
     * @return PDO|null
     */

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            // Configura o PDO para lançar exceções em caso de erro
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Em ambiente de produção, não mostre a mensagem de erro detalhada!
            die("Erro de conexão: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
