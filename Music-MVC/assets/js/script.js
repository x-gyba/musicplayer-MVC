// Versão Music Player de Filipe Cruz

const player = {
    audio: null,
    playlist: [],
    currentIndex: 0,
    isPlaying: false,

    /**
     * Inicializa o player, pega elementos e configura eventos.
     */
    init() {
        // Pega elementos
        this.audio = document.getElementById('audioPlayer');
        this.playBtn = document.getElementById('playBtn');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.progressBar = document.querySelector('.progress-bar');
        this.progress = document.getElementById('progress');
        this.currentTime = document.getElementById('currentTime');
        this.duration = document.getElementById('duration');
        this.trackName = document.getElementById('trackName');
        this.volumeSlider = document.getElementById('volumeSlider');
        this.albumIcon = document.getElementById('albumIcon');

        // Carrega playlist do HTML e configura eventos de clique
        this.loadPlaylist();

        // Configuração de Eventos
        this.playBtn.onclick = () => this.togglePlay();
        this.prevBtn.onclick = () => this.prev();
        this.nextBtn.onclick = () => this.next();
        this.progressBar.onclick = (e) => this.seek(e);
        this.volumeSlider.oninput = (e) => this.setVolume(e.target.value);

        // Eventos do áudio
        this.audio.ontimeupdate = () => this.updateProgress();
        this.audio.onloadedmetadata = () => this.updateDuration();
        this.audio.onended = () => this.next(); // Loop: Ao terminar, chama a próxima música

        // Inicializa Menu mobile
        this.initMenu();

        // Volume inicial (70%) e carrega a primeira faixa
        this.audio.volume = 0.7;
        if (this.playlist.length > 0) {
            this.loadTrack(0);
        }

        console.log(`Player iniciado! ${this.playlist.length} músicas carregadas.`);
    },

    /**
     * Carrega os dados da playlist a partir do DOM.
     */
    loadPlaylist() {
        const items = document.querySelectorAll('.playlist-item');
        
        items.forEach((item, i) => {
            // Pega o nome da música (primeiro span) e o SRC do data-attribute
            const name = item.querySelector('span:first-of-type').textContent.trim();
            const src = item.dataset.src;
            
            if (src) {
                this.playlist.push({ name, src });
                
                // Configura o clique para carregar a faixa e tentar tocar
                item.onclick = () => {
                    this.loadTrack(i);
                    this.play();
                };
            }
        });
    },
    
    /**
     * Carrega uma faixa específica pelo índice.
     * @param {number} index - O índice da faixa.
     */
    loadTrack(index) {
        if (index < 0 || index >= this.playlist.length) return;
        
        this.currentIndex = index;
        const track = this.playlist[index];
        
        this.audio.src = track.src;
        this.trackName.textContent = track.name;
        
        // Marca o item da playlist como ativo
        document.querySelectorAll('.playlist-item').forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
    },

    /**
     * Alterna entre tocar e pausar.
     */
    togglePlay() {
        if (this.isPlaying) {
            this.pause();
        } else {
            this.play();
        }
    },

    /**
     * Tenta reproduzir o áudio.
     */
    play() {
        const playPromise = this.audio.play();

        // Tratamento de Promise para lidar com bloqueios de autoplay
        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    this.isPlaying = true;
                    this.playBtn.querySelector('i').className = 'fas fa-pause';
                    if (this.albumIcon) this.albumIcon.style.animationPlayState = 'running';
                })
                .catch(error => {
                    console.warn("Reprodução bloqueada pelo navegador. Clique no botão Play.", error);
                    this.isPlaying = false;
                    this.playBtn.querySelector('i').className = 'fas fa-play'; 
                    if (this.albumIcon) this.albumIcon.style.animationPlayState = 'paused';
                });
        }
    },

    /**
     * Pausa a reprodução.
     */
    pause() {
        this.audio.pause();
        this.isPlaying = false;
        this.playBtn.querySelector('i').className = 'fas fa-play';
        if (this.albumIcon) this.albumIcon.style.animationPlayState = 'paused';
    },

    /**
     * Avança para a próxima faixa (com loop).
     */
    next() {
        let next = this.currentIndex + 1;
        if (next >= this.playlist.length) next = 0; // Volta para o início
        this.loadTrack(next);
        this.play();
    },

    /**
     * Volta para a faixa anterior (com loop).
     */
    prev() {
        let prev = this.currentIndex - 1;
        if (prev < 0) prev = this.playlist.length - 1; // Volta para o final
        this.loadTrack(prev);
        this.play();
    },

    /**
     * Atualiza a barra de progresso e o tempo decorrido.
     */
    updateProgress() {
        if (!this.audio.duration) return;
        
        const percent = (this.audio.currentTime / this.audio.duration) * 100;
        this.progress.style.width = percent + '%';
        this.currentTime.textContent = this.formatTime(this.audio.currentTime);
    },

    /**
     * Atualiza o tempo total da música.
     */
    updateDuration() {
        if (this.audio.duration) {
            this.duration.textContent = this.formatTime(this.audio.duration);
        }
    },

    /**
     * Permite pular para um ponto da música ao clicar na barra de progresso.
     * @param {MouseEvent} e - Evento de clique.
     */
    seek(e) {
        const width = this.progressBar.clientWidth;
        const clickX = e.offsetX;
        if (this.audio.duration) {
            this.audio.currentTime = (clickX / width) * this.audio.duration;
        }
    },

    /**
     * Define o volume e altera o ícone.
     * @param {string} value - O valor do slider (0 a 100).
     */
    setVolume(value) {
        this.audio.volume = value / 100;
        
        const icon = document.querySelector('.volume-control i');
        if (value == 0) icon.className = 'fas fa-volume-mute';
        else if (value < 50) icon.className = 'fas fa-volume-down';
        else icon.className = 'fas fa-volume-up';
    },

    /**
     * Formata segundos para o formato M:SS.
     * @param {number} seconds - Tempo em segundos.
     * @returns {string} Tempo formatado.
     */
    formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    },

    /**
     * Inicializa a funcionalidade do menu de navegação (mobile).
     */
    initMenu() {
        const toggle = document.getElementById('menuToggle');
        const nav = document.getElementById('nav');
        
        if (!toggle || !nav) return;

        toggle.onclick = () => {
            nav.classList.toggle('active');
            const icon = toggle.querySelector('i');
            
            if (nav.classList.contains('active')) {
                icon.className = 'fas fa-times'; // Ícone de fechar
            } else {
                icon.className = 'fas fa-bars'; // Ícone de menu
            }
        };

        // Fecha o menu ao clicar em um link (útil no mobile)
        nav.querySelectorAll('a').forEach(link => {
            link.onclick = () => {
                if (window.innerWidth <= 768) {
                    nav.classList.remove('active');
                    toggle.querySelector('i').className = 'fas fa-bars';
                }
            };
        });

        // Fecha o menu ao clicar fora
        document.onclick = (e) => {
            if (!nav.contains(e.target) && !toggle.contains(e.target)) {
                if (nav.classList.contains('active')) {
                    nav.classList.remove('active');
                    toggle.querySelector('i').className = 'fas fa-bars';
                }
            }
        };
    }
};

// --------------------------------------------------

// Inicia o player quando o DOM estiver completamente carregado
document.addEventListener('DOMContentLoaded', () => {
    player.init();
});

// Scroll suave para todos os links âncora
document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.onclick = (e) => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    };
});


// scroll suave
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os links de âncora que apontam para uma seção dentro da mesma página
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Previne o comportamento padrão de "salto"
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Obtém a altura do header fixo, se houver, para ajustar o scroll (offset)
                    const header = document.querySelector('.header');
                    const headerHeight = header ? header.offsetHeight : 0;

                    // Calcula a posição do scroll, subtraindo a altura do header
                    const offsetPosition = targetElement.offsetTop - headerHeight;

                    // Aplica a rolagem suave
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });

                    // (Opcional) Se o seu menu mobile usa a classe 'active' para fechar, você pode adicionar esta lógica:
                    const nav = document.querySelector('.nav');
                    if (nav && nav.classList.contains('active')) {
                        nav.classList.remove('active');
                        // Se você tiver um botão toggle, adicione a lógica para desativá-lo visualmente aqui.
                    }
                }
            });
        });
    });
