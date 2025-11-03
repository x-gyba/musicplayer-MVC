<link rel="stylesheet" href="/public/assets/css/header.css">
<!-- Adicione também o link do Boxicons se ainda não estiver no projeto -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<header class="header">
    <a href="#" class="logo">
        <img src="public/assets/images/logo.png" alt="logo" />
        <span>My Shop 2.0</span>
    </a>

    <ul class="navbar">
        <li class="nav-item"><a href="#home" class="nav-link">Início</a></li>
        <li class="nav-item"><a href="#about" class="nav-link">Sobre</a></li>
        <li class="nav-item"><a href="#menu" class="nav-link">Menu</a></li>
        <li class="nav-item"><a href="#" class="nav-link">link</a></li>
        <li class="nav-item"><a href="#contact" class="nav-link">Contato</a></li>
    </ul>

    <!-- Ícones de login e carrinho -->
    <div class="icon">
        <a href="#login" class="login-icon">
            <i class="bx bx-user" id="login-btn"></i>
        </a>
        <i class="bx bx-shopping-bag" id="cart-btn">
            <span class="carrinho-item-qtd" value="1">0</span>
        </i>
    </div>

    <div class="nav-toggle">
        <i class="bx bx-menu"></i>
    </div>

    <!-- Carrinho -->
    <div class="carrinho">
        <div class="header-carrinho">
            <i class="bx bx-x-circle carrinho-close"></i>
            <h2>Meu Carrinho:</h2>
        </div>
        <div class="carrinho-items"></div>
        <div class="carrinho-total">
            <div class="lista">
                <strong>Total:</strong>
                <span class="carrinho-preco-total">R$ 0,00</span>
            </div>
            <button class="btn-checkout">
                <i class="bx bx-cart-alt"></i>Pagar
            </button>
        </div>
    </div>
</header>
