<?php

class App
{
    public function run()
    {
        // Roteamento simples
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'loja/home';
        $parts = explode('/', $url);

        $controller = $parts[0] ?? 'loja';
        $view = $parts[1] ?? 'home';

        $viewPath = ROOT . "/views/{$controller}/{$view}.php";

        if (file_exists($viewPath)) {
            $content = $viewPath;
            
            // --- CORREÇÃO AQUI ---
            
            // 1. Define um título base para a Home Page
            if ($controller === 'loja' && $view === 'home') {
                $title = 'Bem vindo a MyShop 2.0'; 
            } else {
                // Para outras páginas (que não a Home), usa o nome da view como título
                $title = ucfirst($view) . ' | MyShop 2.0'; 
            }
            
            // 2. Inclui o layout.php, que agora usa a nova variável $title
            include ROOT . '/layouts/layout.php';
            
        } else {
            http_response_code(404);
            $title = '404 - Página não encontrada'; // Definir título para 404 também
            $content = ''; // Limpa o conteúdo
            
            echo "<h1>404 - Página não encontrada</h1>";
        }
    }
}

