/* Sistema de Login Modal Otimizado */

const LoginModal = {
  modal: null,
  overlay: null,
  closeBtn: null,
  loginTrigger: null,
  form: null,
  message: null,
  togglePassword: null,
  passwordInput: null,

  /* Inicializa o modal de login */
  init() {
    // Captura elementos
    this.modal = document.getElementById("loginModal");
    this.overlay = document.getElementById("loginOverlay");
    this.closeBtn = document.getElementById("closeModal");
    
    // Captura o gatilho de abertura (prioriza 'loginTrigger', usa 'openModal' como fallback)
    this.loginTrigger = 
        document.getElementById("loginTrigger") ?? 
        document.getElementById("openModal");
    
    this.form = document.getElementById("loginForm");
    this.message = document.getElementById("formMessage");
    this.togglePassword = document.getElementById("togglePassword");
    this.passwordInput = document.getElementById("password");

    if (!this.modal || !this.form) {
      console.error("Elementos do modal de login não encontrados!");
      return;
    }

    // Configura eventos
    this.setupEvents();

    console.log("Modal de login inicializado.");
  },

  /* Configura todos os eventos do modal  */
  setupEvents() {
    /* Abrir modal */
    if (this.loginTrigger) {
      this.loginTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        this.open();
      });
    }

    /* Fechar modal */
    if (this.closeBtn) {
      this.closeBtn.addEventListener("click", () => this.close());
    }

    /* Toggle de senha */
    if (this.togglePassword && this.passwordInput) {
      this.togglePassword.addEventListener("click", () => {
        this.togglePasswordVisibility();
      });
    }

    /* Submit do formulário */
    if (this.form) {
      this.form.addEventListener("submit", (e) => {
        e.preventDefault();
        this.handleLogin();
      });
    }
        
  },

  /* Abre o modal */
  open() {
    this.modal.classList.add("active");
    // Adiciona a classe no body para desabilitar o scroll, se necessário
    document.body.classList.add("modal-open"); 

    // Foca no campo de usuário
    setTimeout(() => {
      document.getElementById("username")?.focus();
    }, 100);
  },

  /**
   * Fecha o modal
   */
  close() {
    this.modal.classList.remove("active");
    document.body.classList.remove("modal-open");
    this.clearForm();
  },

  /**
   * Alterna visibilidade da senha
   */
  togglePasswordVisibility() {
    const type = this.passwordInput.type === "password" ? "text" : "password";
    this.passwordInput.type = type;

    const icon = this.togglePassword.querySelector("i");
    icon.className = type === "password" ? "fas fa-eye" : "fas fa-eye-slash";
  },

  /**
   * Processa o login
   */
  async handleLogin() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!username || !password) {
      this.showMessage("Por favor, preencha todos os campos.", "error");
      return;
    }

    this.setLoading(true);
    this.hideMessage();

    try {
      const response = await fetch("controllers/AuthController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "login",
          username: username,
          password: password,
        }),
      });

      const data = await response.json();

      if (data.success) {
        this.showMessage("Login realizado! Redirecionando...", "success");

        setTimeout(() => {
          window.location.href = "upload.php";
        }, 1000);
      } else {
        this.showMessage(
          data.message || "Usuário ou senha incorretos.",
          "error"
        );
        this.setLoading(false);
      }
    } catch (error) {
      console.error("Erro ao fazer login:", error);
      this.showMessage(
        "Erro ao conectar ao servidor. Tente novamente.",
        "error"
      );
      this.setLoading(false);
    }
  },

  /**
   * Exibe mensagem de feedback
   */
  showMessage(text, type) {
    if (!this.message) return;

    this.message.textContent = text;
    // Garante que a classe 'show' é aplicada sempre que uma mensagem é exibida
    this.message.className = `form-message show ${type}`; 
  },

  /**
   * Esconde mensagem
   */
  hideMessage() {
    if (!this.message) return;
    this.message.classList.remove("show");
    // Opcional: remover as classes de tipo para limpar completamente
    this.message.classList.remove("error", "success"); 
  },

  /**
   * Define estado de loading no botão
   */
  setLoading(isLoading) {
    const btn = document.getElementById("btnLogin");
    if (!btn) return;

    btn.disabled = isLoading;
    if (isLoading) {
      btn.classList.add("loading");
    } else {
      btn.classList.remove("loading");
    }
  },

  /**
   * Limpa o formulário
   */
  clearForm() {
    if (this.form) {
      this.form.reset();
    }
    this.hideMessage();
    this.setLoading(false);
  },
};

// Inicializa quando o DOM estiver pronto
document.addEventListener("DOMContentLoaded", () => {
  LoginModal.init();
});