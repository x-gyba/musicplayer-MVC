<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    /* Constantes privadas de configuração */
    private const HOST = 'localhost';
    private const DBNAME = 'music';
    private const USER = 'root';
    private const PASSWORD = 'xp8x11';

    /* Conexão PDO */
    private PDO $conn;

    /**
     * Construtor: cria a conexão com o banco de dados ao instanciar a classe.
     */
    public function __construct()
    {
        try {
            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, self::USER, self::PASSWORD);
            
            /* Configura atributos da conexão */
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            
        } catch (PDOException $e) {
            /* Encerra a execução e exibe o erro em caso de falha na conexão */
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Método público para acessar o objeto de conexão PDO.
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
?>