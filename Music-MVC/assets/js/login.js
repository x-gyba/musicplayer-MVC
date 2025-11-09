/* ============================================
 * ARQUIVO: js/login.js
 * DESCRIÇÃO: Sistema de Login Modal - VERSÃO CORRIGIDA
 * ============================================ */

class LoginModal {
  constructor() {
    // Inicialização de propriedades
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

    console.log("🔧 Inicializando LoginModal...");
    this.init();
  }

  init() {
    // Captura elementos do DOM
    this.modal = document.getElementById("loginModal");
    this.overlay = document.getElementById("loginOverlay");
    this.closeBtn = document.getElementById("closeModal");
    this.loginTrigger = document.getElementById("loginTrigger") || 
                        document.getElementById("openModal");
    this.form = document.getElementById("loginForm");
    this.message = document.getElementById("formMessage");
    this.togglePassword = document.getElementById("togglePassword");
    this.passwordInput = document.getElementById("password");
    this.usernameInput = document.getElementById("username");
    this.loginButton = document.getElementById("btnLogin");

    // Validação
    if (!this.modal || !this.form) {
      console.error("❌ Elementos essenciais não encontrados!");
      if (!this.modal) console.error("Modal não encontrado: #loginModal");
      if (!this.form) console.error("Formulário não encontrado: #loginForm");
      return;
    }

    this.setupEvents();
    console.log("✅ Modal inicializado com sucesso!");
  }

  setupEvents() {
    // 1. Abrir modal
    if (this.loginTrigger) {
      this.loginTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("🔓 Abrindo modal");
        this.open();
      });
    }

    // 2. Fechar modal (Botão X)
    if (this.closeBtn) {
      this.closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("❌ Fechando via botão X");
        if (this.allowClose) this.close();
      });
    }

    // 3. Fechar modal (Overlay)
    if (this.overlay) {
      this.overlay.addEventListener("click", (e) => {
        if (e.target === this.overlay && this.allowClose) {
          console.log("🖱️ Fechando via overlay");
          this.close();
        }
      });
    }
    
    // 4. Fechar modal (ESC)
    document.addEventListener("keydown", (e) => {
      if (this.isModalOpen && e.key === "Escape" && this.allowClose) {
        console.log("⌨️ Fechando via ESC");
        this.close();
      }
    });

    // 5. Proteger conteúdo do modal
    const modalContent = this.modal.querySelector(".login-modal-content");
    if (modalContent) {
      modalContent.addEventListener("click", (e) => e.stopPropagation());
    }

    // 6. Toggle de senha
    if (this.togglePassword && this.passwordInput) {
      this.togglePassword.addEventListener("click", (e) => {
        e.preventDefault();
        this.togglePasswordVisibility();
      });
    }

    // 7. Submit do formulário
    if (this.form) {
      this.form.addEventListener("submit", (e) => {
        e.preventDefault();
        console.log("📝 Formulário submetido");
        
        if (!this.isSubmitting) {
          this.handleLogin();
        }
      });
    }
  }

  open() {
    if (!this.modal || this.isModalOpen) return;
    
    // Ativa o overlay primeiro
    if (this.overlay) {
      this.overlay.classList.add("active");
    }
    
    // Depois ativa o modal
    this.modal.classList.add("active");
    this.modal.style.display = 'flex';
    
    // Bloqueia scroll do body
    document.body.classList.add("modal-open");
    document.body.style.overflow = 'hidden';
    
    this.isModalOpen = true;
    this.allowClose = true;

    console.log("✅ Modal ABERTO");
    
    // Foco no input após animação
    setTimeout(() => {
      if (this.usernameInput) {
        this.usernameInput.focus();
      }
    }, 150);
  }

  close() {
    if (!this.modal || !this.isModalOpen) return;

    // Remove classes ativas
    this.modal.classList.remove("active");
    if (this.overlay) {
      this.overlay.classList.remove("active");
    }
    
    // Aguarda animação antes de esconder
    setTimeout(() => {
      this.modal.style.display = 'none';
    }, 300);
    
    // Libera scroll do body
    document.body.classList.remove("modal-open");
    document.body.style.overflow = '';
    
    this.isModalOpen = false;
    this.isSubmitting = false;
    this.allowClose = true;
    
    this.clearForm();
    console.log("✅ Modal FECHADO");
  }

  togglePasswordVisibility() {
    if (!this.passwordInput || !this.togglePassword) return;

    const isPassword = this.passwordInput.type === "password";
    this.passwordInput.type = isPassword ? "text" : "password";

    const icon = this.togglePassword.querySelector("i");
    if (icon) {
      icon.classList.toggle('fa-eye-slash', isPassword);
      icon.classList.toggle('fa-eye', !isPassword);
    }
    
    console.log("👁️ Senha:", isPassword ? "visível" : "oculta");
  }

  async handleLogin() {
    console.log("🔐 Iniciando processo de login");

    const username = this.usernameInput ? this.usernameInput.value.trim() : '';
    const password = this.passwordInput ? this.passwordInput.value : '';

    // Validação básica
    if (!username || !password) {
      this.showMessage("Por favor, preencha todos os campos.", "error");
      return;
    }

    // Bloqueia UI
    this.isSubmitting = true;
    this.allowClose = false;
    this.setLoading(true);
    this.hideMessage();

    // Prepara dados
    const formData = new URLSearchParams({
      usuario: username,
      senha: password
    });

    const url = "controllers/AuthController.php?auth_action=login";
    console.log("📤 Enviando para:", url);
    console.log("📦 Dados:", { usuario: username, senha: "***" });

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: formData.toString()
      });

      console.log("📥 Status HTTP:", response.status);
      
      // Captura o texto da resposta primeiro
      const responseText = await response.text();
      console.log("📄 Resposta bruta:", responseText.substring(0, 200));

      // Tenta parsear como JSON
      let data;
      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        console.error("❌ Erro ao parsear JSON:", parseError);
        console.error("Resposta recebida:", responseText);
        throw new Error("Resposta inválida do servidor. Verifique os logs do PHP.");
      }

      console.log("📊 Dados parseados:", data);

      if (data.success) {
        this.showMessage("✅ Login realizado! Redirecionando...", "success");
        console.log("✅ LOGIN BEM-SUCEDIDO!");
        
        setTimeout(() => {
          const redirect = data.redirect || "views/upload.php";
          console.log("🔄 Redirecionando para:", redirect);
          window.location.href = redirect;
        }, 1200);
        
      } else {
        throw new Error(data.message || "Credenciais inválidas");
      }

    } catch (error) {
      console.error("❌ ERRO:", error);
      
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
    console.log(`💬 Mensagem [${type}]:`, text);
  }

  hideMessage() {
    if (!this.message) return;
    
    this.message.classList.remove("show", "error", "success");
    this.message.textContent = '';
  }

  setLoading(isLoading) {
    if (!this.loginButton) return;

    this.loginButton.disabled = isLoading;
    this.loginButton.classList.toggle("loading", isLoading);

    const btnText = this.loginButton.querySelector('.btn-text');
    const btnLoader = this.loginButton.querySelector('.btn-loader');
    
    if (btnText && btnLoader) {
      btnText.style.display = isLoading ? 'none' : 'inline';
      btnLoader.style.display = isLoading ? 'inline' : 'none';
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

/* ========================================
   INICIALIZAÇÃO
======================================== */
document.addEventListener("DOMContentLoaded", () => {
  console.log("📄 DOM carregado - Inicializando sistema de login");
  
  if (window.LoginModalInstance) {
    console.warn("⚠️ Modal já inicializado");
    return;
  }
  
  window.LoginModalInstance = new LoginModal();
  console.log("🎉 Sistema de login pronto!");
});