<?php
/* Incluir o arquivo de configuração de banco de dados */
require_once __DIR__ . '/../config/database.php';
class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

 /* Autentica um usuário 
     *
     * @param string $username
     * @param string $password
     * @return array|false Retorna os dados do usuário (id, username, role) ou false
     */

    public function authenticate($username, $password) {
        // Consulta para buscar o usuário pelo nome
        $query = "SELECT id, username, password, role FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        /* Verifica se o usuário existe */
        /* Verifica a senha criptografada usando password_verify() */
        if ($user && password_verify($password, $user['password'])) {
        /* Senha correta. Remove o hash da senha antes de retornar. */
            unset($user['password']);
            return $user;
        }

        return false; /* Falha na autenticação */
    }
}
?>
