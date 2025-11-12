<?php
// views/login.php
?>
<div class="login-modal-overlay" id="loginOverlay"></div>

<div class="login-modal" id="loginModal">
    <div class="login-modal-content">
        <button class="login-close" id="closeModal" type="button" aria-label="Fechar modal">
            <i class="fas fa-times"></i>
        </button>

        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2>Login</h2>
            <p>Painel Administrativo</p>
        </div>

        <form id="loginForm" class="login-form-modal" method="post" autocomplete="off">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i>
                    Usuário
                </label>
                <input
                    type="text"
                    id="username"
                    name="usuario"
                    placeholder="Digite seu usuário"
                    required
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Senha
                </label>
                <div class="password-input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="senha"
                        placeholder="Digite sua senha"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Mostrar senha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-message" id="formMessage"></div>

            <button type="submit" class="btn-login" id="btnLogin">
                <span class="btn-text">Entrar</span>
                <span class="btn-loader" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>
    </div>
</div>