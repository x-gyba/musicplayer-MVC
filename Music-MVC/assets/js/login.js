/* Sistema de Login Modal - Infogyba 2026 */

class LoginModal {
    constructor() {
        this.modal = null;
        this.overlay = null;
        this.closeBtn = null;
        this.loginTrigger = null;
        this.form = null;
        this.message = null;
        this.togglePassword = null;
        this.passwordInput = null;
        this.usernameInput = null;
        this.loginButton = null;

        this.isModalOpen = false;
        this.isSubmitting = false;
        this.allowClose = true;

        this.init();
    }

    init() {
        /* Captura elementos do DOM */
        this.modal = document.getElementById("loginModal");
        this.overlay = document.getElementById("loginOverlay");
        this.closeBtn = document.getElementById("closeModal");
        this.loginTrigger =
            document.getElementById("loginTrigger") ||
            document.getElementById("openModal");
        this.form = document.getElementById("loginForm");
        this.message = document.getElementById("formMessage");
        this.togglePassword = document.getElementById("togglePassword");
        this.passwordInput = document.getElementById("password");
        this.usernameInput = document.getElementById("username");
        this.loginButton = document.getElementById("btnLogin");

        if (!this.modal || !this.form) {
            console.error("Elementos essenciais não encontrados!");
            return;
        }

        this.setupEvents();
    }

    setupEvents() {
        /* 1. Abrir modal */
        if (this.loginTrigger) {
            this.loginTrigger.addEventListener("click", (e) => {
                e.preventDefault();
                this.open();
            });
        }

        /* 2. Fechar modal (Botão X e Overlay) */
        if (this.closeBtn) {
            this.closeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (this.allowClose) this.close();
            });
        }

        if (this.overlay) {
            this.overlay.addEventListener("click", (e) => {
                if (e.target === this.overlay && this.allowClose) this.close();
            });
        }

        /* 3. Fechar modal (ESC) */
        document.addEventListener("keydown", (e) => {
            if (this.isModalOpen && e.key === "Escape" && this.allowClose) {
                this.close();
            }
        });

        /* 4. Proteger conteúdo do modal */
        const modalContent = this.modal.querySelector(".login-modal-content");
        if (modalContent) {
            modalContent.addEventListener("click", (e) => e.stopPropagation());
        }

        /* 5. Toggle de senha */
        if (this.togglePassword && this.passwordInput) {
            this.togglePassword.addEventListener("click", (e) => {
                e.preventDefault();
                this.togglePasswordVisibility();
            });
        }

        /* 6. Submit do formulário */
        if (this.form) {
            this.form.addEventListener("submit", (e) => {
                e.preventDefault();
                if (!this.isSubmitting) {
                    this.handleLogin();
                }
            });
        }
    }

    open() {
        if (!this.modal || this.isModalOpen) return;

        if (this.overlay) this.overlay.classList.add("active");
        this.modal.classList.add("active");
        this.modal.style.display = "flex";

        document.body.classList.add("modal-open");
        document.body.style.overflow = "hidden";

        this.isModalOpen = true;
        this.allowClose = true;

        setTimeout(() => {
            if (this.usernameInput) this.usernameInput.focus();
        }, 150);
    }

    close() {
        if (!this.modal || !this.isModalOpen) return;

        this.modal.classList.remove("active");
        if (this.overlay) this.overlay.classList.remove("active");

        setTimeout(() => {
            this.modal.style.display = "none";
        }, 300);

        document.body.classList.remove("modal-open");
        document.body.style.overflow = "";

        this.isModalOpen = false;
        this.isSubmitting = false;
        this.allowClose = true;

        this.clearForm();
    }

    togglePasswordVisibility() {
        if (!this.passwordInput || !this.togglePassword) return;

        const isPassword = this.passwordInput.type === "password";
        this.passwordInput.type = isPassword ? "text" : "password";

        const icon = this.togglePassword.querySelector("i");
        if (icon) {
            icon.classList.toggle("fa-eye-slash", isPassword);
            icon.classList.toggle("fa-eye", !isPassword);
        }
    }

    /**
     * MÉTODO PRINCIPAL: Processa o login (Enviando senha em texto puro)
     */
    async handleLogin() {
        const username = this.usernameInput ? this.usernameInput.value.trim() : "";
        const password = this.passwordInput ? this.passwordInput.value : "";

        /* 1. Validação básica */
        if (!username || !password) {
            this.showMessage("Por favor, preencha todos os campos.", "error");
            return;
        }

        /* 2. Bloqueia UI */
        this.isSubmitting = true;
        this.allowClose = false;
        this.setLoading(true);
        this.hideMessage();

        /* 3. Prepara dados */
        const formData = new URLSearchParams();
        formData.append('usuario', username);
        formData.append('senha', password);

        /* 4. Detecta caminho correto */
        const currentPath = window.location.pathname;
        let basePath = currentPath.includes('/views/') ? '../' : '';
        const url = `${basePath}controllers/AuthController.php?auth_action=login`;

        try {
            /* 5. Envia requisição */
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: formData.toString(),
                credentials: 'same-origin'
            });

            /* 6. Processa resposta */
            let data;
            const contentType = response.headers.get("Content-Type");

            if (contentType && contentType.includes("application/json")) {
                data = await response.json();
            } else {
                const responseText = await response.text();
                throw new Error("Resposta inesperada do servidor: " + responseText);
            }

            /* 7. Processa resultado */
            if (data.success === true) {
                this.showMessage("Login realizado! Redirecionando...", "success");

                setTimeout(() => {
                    const redirect = data.redirect || "views/upload.php";
                    window.location.href = redirect;
                }, 1200);

            } else {
                throw new Error(data.message || "Credenciais inválidas");
            }

        } catch (error) {
            this.showMessage(
                error.message || "Erro ao conectar com o servidor",
                "error"
            );

            this.isSubmitting = false;
            this.allowClose = true;
            this.setLoading(false);
        }
    }

    showMessage(text, type) {
        if (!this.message) return;
        this.message.textContent = text;
        this.message.className = `form-message show ${type}`;
    }

    hideMessage() {
        if (!this.message) return;
        this.message.classList.remove("show", "error", "success");
        this.message.textContent = "";
    }

    setLoading(isLoading) {
        if (!this.loginButton) return;

        this.loginButton.disabled = isLoading;
        this.loginButton.classList.toggle("loading", isLoading);

        const btnText = this.loginButton.querySelector(".btn-text");
        const btnLoader = this.loginButton.querySelector(".btn-loader");

        if (btnText && btnLoader) {
            btnText.style.display = isLoading ? "none" : "inline";
            btnLoader.style.display = isLoading ? "inline" : "none";
        }
    }

    clearForm() {
        if (this.form) this.form.reset();
        this.hideMessage();
        this.setLoading(false);
    }
}

/* ==========================================
 * INICIALIZAÇÃO
 * ========================================== */

document.addEventListener("DOMContentLoaded", () => {
    if (window.LoginModalInstance) return;
    window.LoginModalInstance = new LoginModal();
});
