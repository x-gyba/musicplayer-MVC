<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filipe Cruz - Arcanjo Miguel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/whatsapp.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo-area">
                <div class="logo">
                    <i class="fas fa-music"></i>
                    <h1>Filipe Cruz</h1>
                </div>
                <div class="container-inline">
                    <h2>Mas Deus escolheu as coisas loucas deste mundo para confundir as sábias.<span>&nbsp;1Co 1:27</span></h2>
                    
                </div>
            </div>

            <nav class="nav" id="nav">
                <a href="#home">Inicio</a>
                <a href="#music">Músicas</a>
                <a href="#about">Sobre</a>
                <a href="#" id="loginTrigger">Login</a>
                <a href="#contact">Contato</a>
            </nav>

            <div class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Modal de Login -->
    <div class="login-modal" id="loginModal">
        <div class="login-modal-overlay" id="loginOverlay"></div>
        <div class="login-modal-content">
            <button class="login-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h2>Login</h2>
                <p>Painel Administrativo</p>
            </div>
            <form id="loginForm" class="login-form-modal">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Usuário
                    </label>
                    <input type="text" id="username" name="username" placeholder="Digite seu usuário" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Senha
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Digite sua senha" required autocomplete="current-password">
                        <button type="button" class="toggle-password" id="togglePassword">
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

    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h2>Bem-vindo ao meu universo musical</h2>
                <p>Explore minhas últimas criações e deixe a música tocar sua alma</p>
                <a href="#music" class="btn-primary">Ouvir Agora</a>
            </div>
        </div>
    </section>

    <section class="player-section" id="music">
        <div class="container">
            <div class="player-container">
                <div class="player-info">
                    <div class="album-art">
                        <i class="fas fa-compact-disc fa-spin" id="albumIcon"></i>
                    </div>
                    <div class="track-info">
                        <h3 id="trackName">Selecione uma musica</h3>
                </div>
                </div>
                <div class="player-controls">
                    <div class="progress-container">
                        <span class="time" id="currentTime">0:00</span>
                        <div class="progress-bar">
                            <div class="progress" id="progress"></div>
                        </div>
                        <span class="time" id="duration">0:00</span>
                    </div>
                    <div class="control-buttons">
                        <button class="btn-control" id="prevBtn" title="Anterior">
                            <i class="fas fa-backward"></i>
                        </button>
                        <button class="btn-control btn-play" id="playBtn" title="Play/Pause">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn-control" id="nextBtn" title="Próximo">
                            <i class="fas fa-forward"></i>
                        </button>
                        <div class="volume-control">
                            <i class="fas fa-volume-up"></i>
                            <input type="range" id="volumeSlider" min="0" max="100" value="70">
                        </div>
                    </div>
                </div>
                <div class="playlist">
                    <h3>Playlist</h3>
                    <ul id="playlistContainer">
                        <li class="playlist-item" data-src="music/Gabriel Guedes - Santo Pra Sempre (Ao Vivo) .mp3">
                            <i class="fas fa-music"></i>
                            <span>Gabriel Guedes - Santo Pra Sempre</span>
                            <span class="duration">0:00</span>
                        </li>
                        <li class="playlist-item" data-src="music/ Aline Barros - Consagração _ Louvor ao Rei (Ao Vivo).mp3">
                            <i class="fas fa-music"></i>
                            <span>Aline Barros - Consagração _ Louvor ao Rei (Ao Vivo)</span>
                            <span class="duration">0:00</span>
                        </li>
                        <li class="playlist-item" data-src="https://cdn.pixabay.com/download/audio/2024/02/10/audio_55a2992982.mp3?filename=beat-box-music-173620.mp3">
                            <i class="fas fa-music"></i>
                            <span>Test Beat</span>
                            <span class="duration">0:00</span>
                        </li>
                        <li class="playlist-item" data-src="https://cdn.pixabay.com/download/audio/2023/04/10/audio_f84236a997.mp3?filename=corporate-soft-background-music-144079.mp3">
                            <i class="fas fa-music"></i>
                            <span>Soft Cinematic Track</span>
                            <span class="duration">0:00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <h2>Quero oferecer sacrifício de louvor.</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>Mas a hora vem, e agora é, em que os verdadeiros adoradores adorarão o Pai em espírito e em verdade;</p>
                    <p>porque o Pai procura a tais que assim o adorem.</p>
                    <p>Deus é Espírito, e importa que os que o adoram o adorem em espírito e em verdade.<span>João 4:24</span></p>
                </div>
                <div class="about-stats">
                    <div class="stat">
                        <h3>100+</h3>
                        <p>Musicas</p>
                    </div>
                    <div class="stat">
                        <h3>50K+</h3>
                        <p>Ouvintes</p>
                    </div>
                    <div class="stat">
                        <h3>5+</h3>
                        <p>Albuns</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <h2>Fale Conosco</h2>
            <p style="text-align: center; margin-bottom: 40px;">Envie uma mensagem e entrarei em contato em breve</p>

            <div class="contact-wrapper">
                <form id="contactForm" class="contact-form" action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-user"></i> Nome
                            </label>
                            <input type="text" id="name" name="name" placeholder="Seu nome completo" required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class="fas fa-tag"></i> Assunto
                        </label>
                        <input type="text" id="subject" name="subject" placeholder="Assunto da mensagem" required>
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class="fas fa-comment"></i> Mensagem
                        </label>
                        <textarea id="message" name="message" rows="6" placeholder="Escreva sua mensagem aqui..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane"></i> Enviar Mensagem
                        </span>
                        <span class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Enviando...
                        </span>
                    </button>

                    <div id="formStatus" class="form-status"></div>
                </form>

                <div class="contact-info">
                    <h3>Informações de Contato</h3>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Teresópolis, Rio de Janeiro, Brasil</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>contato@filipecruz.com</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span>+55 (21) 9xxxx-xxxx</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="social-section">
        <div class="container">
            <h3>Siga-me nas Redes Sociais</h3>
            <div class="social-links">
                <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" class="social-link" aria-label="Spotify"><i class="fab fa-spotify"></i></a>
                <a href="#" class="social-link" aria-label="SoundCloud"><i class="fab fa-soundcloud"></i></a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Infogyba Soluções em Ti. Todos os direitos reservados.</p>
        </div>
    </footer>

    <a href="https://wa.me/5521999999999?text=Olá!%20Vim%20do%20seu%20site%20e%20gostaria%20de%20mais%20informações." class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Fale conosco no WhatsApp">
        <div class="whatsapp-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
        <span class="whatsapp-text"></span>
    </a>

    <audio id="audioPlayer"></audio>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/contact.js"></script>
</body>
</html>