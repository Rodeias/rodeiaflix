/* RodeiaFlix - Script Interativo */

document.addEventListener('DOMContentLoaded', () => {

    // Efeito no Header ao fazer Scroll
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

});

// Função para abrir o Leitor de Vídeo 
function abrirPlayer(youtubeId, filmeId) {
    console.log("A abrir filme:", youtubeId, filmeId);

    // Registar visualização em segundo plano se o ID existir
    if (filmeId) {
        fetch('catalog.php?ver=' + filmeId).catch(err => console.log(err));
    }
    
    const modal = document.getElementById('playerModal');
    const iframe = document.getElementById('videoIframe');
    
    if (modal && iframe) {
        iframe.src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
        modal.classList.add('active');
        modal.style.display = 'flex';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    } else {
        alert('Erro: Leitor de vídeo não encontrado na página.');
    }
}

// Função para fechar o Leitor
function fecharPlayer() {
    const modal = document.getElementById('playerModal');
    const iframe = document.getElementById('videoIframe');
    
    if (modal && iframe) {
        iframe.src = '';
        modal.classList.remove('active');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Fechar com a tecla ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        fecharPlayer();
    }
});