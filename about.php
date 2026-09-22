<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>Sobre - RodeiaFlix</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <header>
        <div class="logo">RodeiaFlix</div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="about.php">Sobre</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="catalog.php">Catálogo</a></li>
                    <li><a href="logout.php" class="btn-primary">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-primary">Iniciar Sessão</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="content-section" style="margin-top: 100px; max-width: 1200px; margin-left: auto; margin-right: auto;">
        <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 20px;">Sobre o RodeiaFlix</h1>
        <p style="font-size: 1.25rem; line-height: 1.7; color: var(--text-gray); margin-bottom: 50px;">
            O <strong>RodeiaFlix</strong> é uma plataforma de streaming inspirada no design oficial da Netflix, concebida para proporcionar uma navegação fluida, moderna e 100% responsiva na gestão e reprodução de trailers em alta definição.
        </p>

        <h2 class="section-title">Tecnologias Utilizadas</h2>
        <div class="grid-movies" style="margin-top: 20px;">
            <div class="movie-card" style="padding: 30px; text-align: center;">
                <h3 style="color: var(--primary-color); font-size: 1.5rem; margin-bottom: 10px;">HTML5 & CSS3</h3>
                <p style="color: var(--text-gray);">Design responsivo, variáveis CSS, efeito de vidro (*glassmorphism*) e animações com *zoom* e transições suaves.</p>
            </div>
            <div class="movie-card" style="padding: 30px; text-align: center;">
                <h3 style="color: var(--primary-color); font-size: 1.5rem; margin-bottom: 10px;">JavaScript</h3>
                <p style="color: var(--text-gray);">Interatividade com modal, manipulação dinâmica do leitor de vídeos, eventos de scroll e envio de dados em segundo plano.</p>
            </div>
            <div class="movie-card" style="padding: 30px; text-align: center;">
                <h3 style="color: var(--primary-color); font-size: 1.5rem; margin-bottom: 10px;">PHP & MySQL</h3>
                <p style="color: var(--text-gray);">Autenticação segura com encriptação, gestão de papéis (*roles*), upload direto de capas e controlo de histórico.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> RodeiaFlix. Todos os direitos reservados.</p>
        <p>Site desenvolvido por <strong>Marco Rodeia</strong> | Tecnologias: HTML5, CSS3, JavaScript, PHP e MySQL</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>