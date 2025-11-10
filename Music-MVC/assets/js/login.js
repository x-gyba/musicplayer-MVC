/* Sistema de Login Modal - VERSÃO FINAL CORRIGIDA */

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

    console.log("🚀 Inicializando LoginModal...");
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
      console.error("❌ Elementos essenciais não encontrados!");
      if (!this.modal) console.error("   - Modal não encontrado: #loginModal");
      if (!this.form) console.error("   - Formulário não encontrado: #loginForm");
      return;
    }

    this.setupEvents();
    console.log("✅ Modal inicializado com sucesso!");
  }

  setupEvents() {
    /* 1. Abrir modal */
    if (this.loginTrigger) {
      this.loginTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("📂 Abrindo modal");
        this.open();
      });
    }

    /* 2. Fechar modal (Botão X) */
    if (this.closeBtn) {
      this.closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.allowClose) {
          console.log("❌ Fechando via botão X");
          this.close();
        }
      });
    }

    /* 3. Fechar modal (Overlay) */
    if (this.overlay) {
      this.overlay.addEventListener("click", (e) => {
        if (e.target === this.overlay && this.allowClose) {
          console.log("❌ Fechando via overlay");
          this.close();
        }
      });
    }

    /* 4. Fechar modal (ESC) */
    document.addEventListener("keydown", (e) => {
      if (this.isModalOpen && e.key === "Escape" && this.allowClose) {
        console.log("❌ Fechando via ESC");
        this.close();
      }
    });

    /* 5. Proteger conteúdo do modal */
    const modalContent = this.modal.querySelector(".login-modal-content");
    if (modalContent) {
      modalContent.addEventListener("click", (e) => e.stopPropagation());
    }

    /* 6. Toggle de senha */
    if (this.togglePassword && this.passwordInput) {
      this.togglePassword.addEventListener("click", (e) => {
        e.preventDefault();
        this.togglePasswordVisibility();
      });
    }

    /* 7. Submit do formulário */
    if (this.form) {
      this.form.addEventListener("submit", (e) => {
        e.preventDefault();
        
        if (!this.isSubmitting) {
          console.log("📝 Formulário submetido");
          this.handleLogin();
        } else {
          console.warn("⏳ Aguardando resposta anterior...");
        }
      });
    }
  }

  open() {
    if (!this.modal || this.isModalOpen) return;

    if (this.overlay) {
      this.overlay.classList.add("active");
    }

    this.modal.classList.add("active");
    this.modal.style.display = "flex";

    document.body.classList.add("modal-open");
    document.body.style.overflow = "hidden";

    this.isModalOpen = true;
    this.allowClose = true;

    console.log("✅ Modal ABERTO");

    setTimeout(() => {
      if (this.usernameInput) {
        this.usernameInput.focus();
      }
    }, 150);
  }

  close() {
    if (!this.modal || !this.isModalOpen) return;

    this.modal.classList.remove("active");
    if (this.overlay) {
      this.overlay.classList.remove("active");
    }

    setTimeout(() => {
      this.modal.style.display = "none";
    }, 300);

    document.body.classList.remove("modal-open");
    document.body.style.overflow = "";

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
      icon.classList.toggle("fa-eye-slash", isPassword);
      icon.classList.toggle("fa-eye", !isPassword);
    }

    console.log("👁️ Senha:", isPassword ? "visível" : "oculta");
  }

  /**
   * MÉTODO PRINCIPAL: Processa o login
   */
  async handleLogin() {
    console.log("\n==========================================");
    console.log("🔐 INICIANDO PROCESSO DE LOGIN");
    console.log("==========================================");

    const username = this.usernameInput ? this.usernameInput.value.trim() : "";
    const password = this.passwordInput ? this.passwordInput.value : "";

    /* 1. Validação básica */
    if (!username || !password) {
      console.warn("⚠️ Campos vazios detectados");
      this.showMessage("Por favor, preencha todos os campos.", "error");
      return;
    }

    console.log("📋 Credenciais capturadas:");
    console.log("   - Usuário:", username);
    console.log("   - Senha:", "[" + password.length + " caracteres]");

    /* 2. Bloqueia UI */
    this.isSubmitting = true;
    this.allowClose = false;
    this.setLoading(true);
    this.hideMessage();

    /* 3. Prepara dados (EXATAMENTE como AuthController espera) */
    const formData = new URLSearchParams();
    formData.append('usuario', username);
    formData.append('senha', password);

    console.log("📦 FormData preparado:");
    console.log("   - Content-Type: application/x-www-form-urlencoded");
    console.log("   - Body:", formData.toString());

    /* 4. Detecta caminho correto */
    const currentPath = window.location.pathname;
    console.log("📍 Caminho atual da página:", currentPath);

    let basePath = '';
    
    if (currentPath.includes('/views/')) {
      // Estamos em /views/alguma_pagina.php
      basePath = '../';
      console.log("   → Detectado: dentro de /views/");
    } else if (currentPath.includes('/Music-MVC/')) {
      // Estamos na raiz do projeto
      basePath = '';
      console.log("   → Detectado: raiz do projeto");
    } else {
      // Fallback
      basePath = '';
      console.log("   → Usando fallback");
    }

    const url = `${basePath}controllers/AuthController.php?auth_action=login`;
    
    console.log("📡 Requisição será enviada para:");
    console.log("   - URL relativa:", url);
    console.log("   - URL absoluta:", new URL(url, window.location.origin).href);

    try {
      console.log("⏳ Enviando requisição...");
      
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formData.toString(),
        credentials: 'same-origin' // Importante para sessões
      });

      console.log("\n📊 RESPOSTA RECEBIDA:");
      console.log("   - Status HTTP:", response.status, response.statusText);
      console.log("   - URL final:", response.url);
      console.log("   - Headers:", Object.fromEntries(response.headers.entries()));

      /* 5. Captura resposta como texto primeiro */
      const responseText = await response.text();
      
      console.log("\n📄 Resposta bruta (primeiros 500 caracteres):");
      console.log(responseText.substring(0, 500));
      
      if (responseText.length > 500) {
        console.log("... (total de " + responseText.length + " caracteres)");
      }

      /* 6. Tenta parsear JSON */
      let data;
      try {
        data = JSON.parse(responseText);
        console.log("\n✅ JSON parseado com sucesso:");
        console.log(data);
      } catch (parseError) {
        console.error("\n❌ ERRO AO PARSEAR JSON:");
        console.error("   - Erro:", parseError.message);
        console.error("\n📄 Resposta COMPLETA do servidor:");
        console.error(responseText);
        
        // Diagnóstico de erros comuns
        if (response.status === 404) {
          throw new Error(
            "Controlador não encontrado (404). Verifique:\n" +
            "1. O arquivo AuthController.php existe em /controllers/\n" +
            "2. O caminho está correto\n" +
            "3. Permissões de leitura do arquivo"
          );
        }
        
        if (response.status === 500) {
          throw new Error(
            "Erro interno do servidor (500). Verifique:\n" +
            "1. Os logs do PHP no servidor\n" +
            "2. Conexão com banco de dados\n" +
            "3. Sintaxe do AuthController.php"
          );
        }
        
        if (responseText.includes("<?php") || responseText.includes("<!DOCTYPE")) {
          throw new Error(
            "O servidor retornou HTML em vez de JSON. Possíveis causas:\n" +
            "1. Erro de PHP antes do header JSON\n" +
            "2. Caminho incorreto redirecionando para página HTML\n" +
            "3. Arquivo não sendo processado como PHP"
          );
        }
        
        throw new Error(
          "Resposta inválida do servidor. Verifique os logs do PHP.\n" +
          "Resposta: " + responseText.substring(0, 200)
        );
      }

      /* 7. Processa resposta de sucesso ou erro */
      if (data.success === true) {
        console.log("\n✅ LOGIN BEM-SUCEDIDO!");
        console.log("   - Mensagem:", data.message);
        
        if (data.user) {
          console.log("   - Dados do usuário:");
          console.log("     • ID:", data.user.id);
          console.log("     • Nome:", data.user.usuario);
          console.log("     • Email:", data.user.email);
        }
        
        this.showMessage("Login realizado! Redirecionando...", "success");

        setTimeout(() => {
          const redirect = data.redirect || "views/upload.php";
          console.log("↪️  Redirecionando para:", redirect);
          console.log("==========================================\n");
          window.location.href = redirect;
        }, 1200);
        
      } else {
        // Login falhou
        console.error("\n❌ LOGIN FALHOU:");
        console.error("   - Mensagem:", data.message);
        console.log("==========================================\n");
        
        throw new Error(data.message || "Credenciais inválidas");
      }

    } catch (error) {
      console.error("\n💥 ERRO CAPTURADO:");
      console.error("   - Tipo:", error.name);
      console.error("   - Mensagem:", error.message);
      console.error("   - Stack:", error.stack);
      console.log("==========================================\n");

      this.showMessage(
        error.message || "Erro ao conectar com o servidor",
        "error"
      );

      // Desbloqueia UI em caso de erro
      this.isSubmitting = false;
      this.allowClose = true;
      this.setLoading(false);
    }
  }

  showMessage(text, type) {
    if (!this.message) return;

    this.message.textContent = text;
    this.message.className = `form-message show ${type}`;
    console.log(`💬 Exibindo mensagem [${type}]: ${text}`);
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

    console.log(isLoading ? "⏳ Loading ativado" : "✅ Loading desativado");
  }

  clearForm() {
    if (this.form) {
      this.form.reset();
    }
    this.hideMessage();
    this.setLoading(false);
  }
}

/* ==========================================
 * INICIALIZAÇÃO
 * ========================================== */

document.addEventListener("DOMContentLoaded", () => {
  console.log("\n==========================================");
  console.log("📄 DOM CARREGADO");
  console.log("==========================================");
  console.log("🌐 URL atual:", window.location.href);
  console.log("📍 Pathname:", window.location.pathname);
  console.log("🏠 Origin:", window.location.origin);
  console.log("==========================================\n");

  if (window.LoginModalInstance) {
    console.warn("⚠️ Modal já foi inicializado anteriormente");
    return;
  }

  window.LoginModalInstance = new LoginModal();
  console.log("🎉 Sistema de login pronto para uso!\n");
});