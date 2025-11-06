/* Sistema de Login Modal - CLASSE OTIMIZADA */
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

    console.log("🔧 Inicializando LoginModal...");
    this.init();
  }

  init() {
    // 1. Captura elementos do DOM
    this.modal = document.getElementById("loginModal");
    this.overlay = document.getElementById("loginOverlay");
    this.closeBtn = document.getElementById("closeModal");
    this.loginTrigger =
      document.getElementById("loginTrigger") ||
      document.getElementById("openModal");
    this.form = document.getElementById("loginForm");
    this.message = document.getElementById("formMessage");
    this.togglePassword = document.getElementById("togglePassword");
    
    // **Aprimoramento:** Captura inputs e botão para uso em outros métodos
    this.passwordInput = document.getElementById("password");
    this.usernameInput = document.getElementById("username");
    this.loginButton = document.getElementById("btnLogin");


    // 2. Validação de elementos essenciais
    if (!this.modal) {
      console.error("❌ Modal não encontrado! Verifique o ID 'loginModal'");
      return;
    }
    if (!this.form) {
      console.error("❌ Formulário não encontrado! Verifique o ID 'loginForm'");
      return;
    }

    this.setupEvents();
    console.log("✅ Modal de login inicializado com sucesso!");
  }

  setupEvents() {
    // 1️⃣ ABRIR MODAL
    if (this.loginTrigger) {
      this.loginTrigger.addEventListener("click", this.handleOpen.bind(this));
    }

    // 2️⃣ FECHAR MODAL - BOTÃO X
    if (this.closeBtn) {
      this.closeBtn.addEventListener("click", this.handleClose.bind(this));
    } else {
      console.warn("⚠️ Botão de fechar não encontrado!");
    }

    // 3️⃣ FECHAR COM TECLA ESC
    document.addEventListener("keydown", (e) => {
      if (this.modal.classList.contains("active") && e.key === "Escape") {
        console.log("⌨️ ESC pressionado - Fechando modal");
        this.close();
      }
    });

    // 4️⃣ PROTEGER CONTEÚDO DO MODAL (Impede clique "vazado" no overlay)
    const modalContent = this.modal.querySelector(".login-modal-content");
    if (modalContent) {
      modalContent.addEventListener("click", (e) => e.stopPropagation());
    }

    // 5️⃣ TOGGLE DE SENHA
    if (this.togglePassword && this.passwordInput) {
      this.togglePassword.addEventListener("click", (e) => {
        e.preventDefault();
        this.togglePasswordVisibility();
      });
    }

    // 6️⃣ SUBMIT DO FORMULÁRIO
    if (this.form) {
      this.form.addEventListener("submit", this.handleLogin.bind(this));
    }
  }
  
  // MÉTODOS AUXILIARES para Events
  handleOpen(e) {
    e.preventDefault();
    console.log("🔓 Abrindo modal...");
    this.open();
  }

  handleClose(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    console.log("❌ Botão X clicado - Fechando modal");
    this.close();
  }


  open() {
    if (!this.modal) return;

    this.modal.classList.add("active");
    document.body.classList.add("modal-open");

    console.log("✅ Modal aberto");

    setTimeout(() => {
      if (this.usernameInput) {
        this.usernameInput.focus();
      }
    }, 100);
  }

  close() {
    if (!this.modal) return;

    console.log("🔒 Fechando modal...");
    this.modal.classList.remove("active");
    document.body.classList.remove("modal-open");
    this.clearForm();
    console.log("✅ Modal fechado");
  }

  togglePasswordVisibility() {
    // Sem duplicação, usando this.passwordInput
    if (!this.passwordInput || !this.togglePassword) return;

    const isPassword = this.passwordInput.type === "password";
    this.passwordInput.type = isPassword ? "text" : "password";

    const icon = this.togglePassword.querySelector("i");
    if (icon) {
      icon.className = isPassword ? "fas fa-eye-slash" : "fas fa-eye";
    }
  }

  async handleLogin(e) {
    e.preventDefault(); // Garante que o preventDefault está aqui

    // Sem duplicação, usando this.usernameInput e this.passwordInput
    const username = this.usernameInput?.value.trim();
    const password = this.passwordInput?.value;

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
  }

  showMessage(text, type) {
    if (!this.message) return;
    this.message.textContent = text;
    this.message.className = `form-message show ${type}`;
  }

  hideMessage() {
    if (!this.message) return;
    this.message.classList.remove("show", "error", "success");
  }

  setLoading(isLoading) {
    // Sem duplicação, usando this.loginButton
    if (!this.loginButton) return;

    this.loginButton.disabled = isLoading;
    if (isLoading) {
      this.loginButton.classList.add("loading");
    } else {
      this.loginButton.classList.remove("loading");
    }
  }

  clearForm() {
    if (this.form) {
      this.form.reset();
    }
    this.hideMessage();
    this.setLoading(false);
  }
}

/* Inicialização */
document.addEventListener("DOMContentLoaded", () => {
  console.log("📄 DOM carregado - Inicializando LoginModal (Classe)");
  // Cria uma nova instância da classe
  window.LoginModalInstance = new LoginModal();
});