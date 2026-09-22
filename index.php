<?php
session_start();
require_once 'db.php';

// Obter filme para o Banner de Destaque
$destaque_res = $conn->query("SELECT * FROM filmes WHERE destaque = 1 LIMIT 1");
$destaque = $destaque_res->fetch_assoc();
if (!$destaque) {
    $destaque_res = $conn->query("SELECT * FROM filmes ORDER BY id DESC LIMIT 1");
    $destaque = $destaque_res->fetch_assoc();
}

$id_youtube_destaque = ($destaque && function_exists('converterYoutubeEmbed')) ? converterYoutubeEmbed($destaque['link_youtube']) : '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>RodeiaFlix - Os Melhores Filmes e Séries</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Estilos e Animações dos Cartões de Vantagens */
        .features-section {
            padding: 60px 5%;
            background: linear-gradient(180deg, #141414 0%, #080808 100%);
            text-align: center;
        }

        .features-title {
            font-size: 2.2rem;
            margin-bottom: 40px;
            color: #fff;
            font-weight: 700;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 35px 25px;
            text-align: left;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
        }

        /* Animação ao passar o rato (Hover) */
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            background: rgba(229, 9, 20, 0.1);
            border-color: #E50914;
            box-shadow: 0 15px 30px rgba(229, 9, 20, 0.25);
        }

        /* Efeito de brilho corrido */
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-icon {
            font-size: 2.8rem;
            margin-bottom: 20px;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .feature-card h3 {
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: #aaa;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .cta-box {
            margin-top: 50px;
            padding: 30px;
            background: rgba(229, 9, 20, 0.08);
            border-radius: 12px;
            border: 1px dashed rgba(229, 9, 20, 0.4);
            display: inline-block;
        }

        .cta-box p {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">RodeiaFlix</div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="catalog.php">Catálogo</a></li>
                <li><a href="about.php">Sobre</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] == 'admin'): ?>
                        <li><a href="admin.php">Painel Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="btn-primary">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-primary">Entrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <?php if ($destaque): ?>
    <section class="hero">
        <div class="video-background">
            <iframe src="https://www.youtube.com/embed/<?= $id_youtube_destaque ?>?autoplay=1&mute=1&controls=0&loop=1&playlist=<?= $id_youtube_destaque ?>" allow="autoplay; encrypted-media"></iframe>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><?= htmlspecialchars($destaque['titulo']) ?></h1>
            <p><?= htmlspecialchars($destaque['descricao']) ?></p>
            <div class="hero-actions">
                <button onclick="abrirPlayer('<?= $id_youtube_destaque ?>', <?= $destaque['id'] ?>)" class="btn-primary" style="font-size: 1.1rem; padding: 12px 24px; cursor: pointer; border: none; border-radius: 4px;">
                    ▶ Ver Trailer em Destaque
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Secção de Cartões Animados com Razões para Registar -->
    <section class="features-section">
        <h2 class="features-title">Porquê Registar-se na RodeiaFlix?</h2>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎬</div>
                <h3>Catálogo Exclusivo</h3>
                <p>Acede a uma seleção completa de filmes e séries em alta definição organizados por categorias para todos os gostos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🕒</div>
                <h3>Histórico Automático</h3>
                <p>Guarda onde paraste! Os utilizadores registados mantêm um histórico dos filmes visualizados para continuar a ver a qualquer hora.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Experiência Rápida</h3>
                <p>Navegação fluida sem interrupções. Leitor direto e leve integrado para assistires aos trailers sem complicações.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>100% Gratuito e Seguro</h3>
                <p>Cria a tua conta em segundos. Sem fidelizações ou custos ocultos! Apenas entretenimento ao teu alcance.</p>
            </div>
        </div>

        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="cta-box">
            <p>Pronto para começar a assistir?</p>
            <a href="register.php" class="btn-primary" style="padding: 12px 30px; font-size: 1.1rem; text-decoration: none; display: inline-block;">
                Criar Conta Grátis
            </a>
        </div>
        <?php else: ?>
        <div class="cta-box">
            <p>Já estás ligado! Explora o nosso catálogo completo.</p>
            <a href="catalog.php" class="btn-primary" style="padding: 12px 30px; font-size: 1.1rem; text-decoration: none; display: inline-block;">
                Ir para o Catálogo
            </a>
        </div>
        <?php endif; ?>
    </section>

    <!-- Modal do Leitor de Vídeo para o Destaque -->
    <div id="playerModal" class="modal-video">
        <div class="modal-content">
            <span class="close-btn" onclick="fecharPlayer()">&times;</span>
            <iframe id="videoIframe" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> RodeiaFlix. Todos os direitos reservados.</p>
        <p>Site desenvolvido por <strong>Marco Rodeia</strong> | Tecnologias: HTML5, CSS3, JavaScript, PHP e MySQL</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>