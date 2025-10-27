<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filipe Cruz - Arcanjo Miguel</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <h2>Mas Deus escolheu as coisas loucas deste mundo para confundir as sábias.</h2>
                    <span>1Co 1:27</span>
                </div>
            </div>
            
            <nav class="nav" id="nav">
                <a href="#home">Inicio</a>
                <a href="#music">Músicas</a>
                <a href="#about">Sobre</a>
                <a href="#login">Login</a>
                <a href="#contact">Contato</a>
            </nav>
            
            <div class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

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
                        <p id="artistName">Filipe Cruz</p>    
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
                        <li class="playlist-item" data-src="https://cdn.pixabay.com/download/audio/2023/12/12/audio_145d41c0ec.mp3?filename=inspiring-ambient-162799.mp3">
                            <i class="fas fa-music"></i>
                            <span>Acoustic Guitar Test</span>
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
            <h2>Sobre</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut lab</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla p</p>    
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

    <section class="login" id="login">
        <div class="container">
            <h2>Área de Login</h2>
            <div class="login-form">
                <p>Em breve...</p>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <h2>Siga-me nas Redes Sociais</h2>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                <a href="#" class="social-link"><i class="fab fa-spotify"></i></a>
                <a href="#" class="social-link"><i class="fab fa-soundcloud"></i></a>   
            </div>    
        </div>    
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Infogyba Soluções em Ti. Todos os direitos reservados.</p>
        </div>
    </footer>

    <audio id="audioPlayer"></audio>

    <script src="assets/js/script.js"></script>
</body>
</html>