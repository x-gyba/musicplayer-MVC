<?php
require_once 'config/database.php';

use Config\Database;
use PDO;
use PDOException;

class User
{
    private PDO $conn;

    /**
     * Construtor: Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Buscar usuário por nome de usuário (username).
     * @param string $usuario O nome de usuário.
     * @return object|null O objeto do usuário ou null se não for encontrado.
     */
    public function findByUsername(string $usuario): ?object
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    /**
     * Criar novo usuário.
     * @param string $usuario O nome de usuário.
     * @param string $senha A senha (será hasheada).
     * @param string $email O email do usuário.
     * @return bool True se a criação for bem-sucedida, False caso contrário.
     */
    public function create(string $usuario, string $senha, string $email): bool
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, senha, email, status) VALUES (:usuario, :senha, :email, 'active')");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':senha', $hash);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            /* Retorna false em caso de erro (ex: usuário duplicado) */
            return false;
        }
    }

    /**
     * Verifica se um usuário com o nome de usuário fornecido já existe.
     * @param string $usuario O nome de usuário a ser verificado.
     * @return bool True se o usuário existir, False caso contrário.
     */
    public function exists(string $usuario): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}