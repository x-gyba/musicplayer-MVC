<?php
// Define o diretório raiz do projeto
define('ROOT', dirname(__DIR__));

// Inicia a sessão
session_start();

// Autoload de classes
spl_autoload_register(function($class) {
    // Converte namespaces em caminhos de diretórios
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Diretórios a serem verificados
    $directories = [
        ROOT . '/config/core',                     // onde está o App.php
        ROOT . '/app/controllers',
        ROOT . '/app/models',
        ROOT . '/app'
    ];

    // Procura e carrega o arquivo da classe
    foreach ($directories as $directory) {
        $file = $directory . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Inclui o arquivo App.php do caminho correto
require_once ROOT . '/config/core/App.php';

// Instancia e executa a aplicação
$app = new App();
$app->run();
