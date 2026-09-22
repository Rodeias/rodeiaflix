<?php
session_start();
require_once 'db.php';

// Garantir que o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Função para extrair o ID do vídeo do YouTube
function obterYoutubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return isset($match[1]) ? $match[1] : $url;
}

// Registo de visualização no histórico via AJAX/Fetch em segundo plano
if (isset($_GET['ver'])) {
    $filme_id = (int)$_GET['ver'];
    $conn->query("INSERT INTO historico (utilizador_id, filme_id) VALUES ($user_id, $filme_id)");
    exit;
}

// Obter filme para o Banner de Destaque
$destaque_res = $conn->query("SELECT * FROM filmes WHERE destaque = 1 LIMIT 1");
$destaque = $destaque_res->fetch_assoc();
if (!$destaque) {
    $destaque_res = $conn->query("SELECT * FROM filmes ORDER BY id DESC LIMIT 1");
    $destaque = $destaque_res->fetch_assoc();
}

$id_youtube_destaque = $destaque ? obterYoutubeId($destaque['link_youtube']) : '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>Catálogo - RodeiaFlix</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <header>
        <div class="logo">RodeiaFlix</div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="catalog.php">Catálogo</a></li>
                <li><a href="about.php">Sobre</a></li>
                <?php if (isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] == 'admin'): ?>
                    <li><a href="admin.php">Painel Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn-primary">Sair</a></li>
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
                <button onclick="abrirPlayer('<?= $id_youtube_destaque ?>', <?= $destaque['id'] ?>)" class="btn-primary" style="font-size: 1.1rem; padding: 12px 24px; cursor: pointer;">
                    ▶ Reproduzir Trailer
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="content-section">
        <!-- Secção Continuar a Ver (Histórico) -->
        <?php
        $sql_hist = "SELECT DISTINCT f.* FROM historico h JOIN filmes f ON h.filme_id = f.id WHERE h.utilizador_id = $user_id ORDER BY h.data_visualizacao DESC LIMIT 4";
        $res_hist = $conn->query($sql_hist);
        if ($res_hist && $res_hist->num_rows > 0):
        ?>
            <h2 class="section-title">Continuar a Ver</h2>
            <div class="grid-movies" style="margin-bottom: 50px;">
                <?php while($h = $res_hist->fetch_assoc()): 
                    $img = file_exists('uploads/' . $h['imagem']) ? 'uploads/' . $h['imagem'] : 'https://via.placeholder.com/300x450?text=Sem+Capa';
                    $yt_id = obterYoutubeId($h['link_youtube']);
                ?>
                    <div class="movie-card" onclick="abrirPlayer('<?= $yt_id ?>', <?= $h['id'] ?>)">
                        <div class="movie-card-img-wrapper">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($h['titulo']) ?>">
                            <div class="movie-card-overlay">
                                <span class="btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">▶ Ver Novamente</span>
                            </div>
                        </div>
                        <div class="movie-info">
                            <h4><?= htmlspecialchars($h['titulo']) ?></h4>
                            <span><?= htmlspecialchars($h['categoria']) ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <!-- Secção Todos os Filmes -->
        <h2 class="section-title">Todos os Filmes</h2>
        <div class="grid-movies">
            <?php
            $sql = "SELECT * FROM filmes ORDER BY id DESC";
            $res = $conn->query($sql);
            while($f = $res->fetch_assoc()):
                $img = file_exists('uploads/' . $f['imagem']) ? 'uploads/' . $f['imagem'] : 'https://via.placeholder.com/300x450?text=Sem+Capa';
                $yt_id = obterYoutubeId($f['link_youtube']);
            ?>
                <div class="movie-card" onclick="abrirPlayer('<?= $yt_id ?>', <?= $f['id'] ?>)">
                    <div class="movie-card-img-wrapper">
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($f['titulo']) ?>">
                        <div class="movie-card-overlay">
                            <span class="btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">▶ Reproduzir</span>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h4><?= htmlspecialchars($f['titulo']) ?></h4>
                        <span><?= htmlspecialchars($f['categoria']) ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Modal do Leitor de Vídeo -->
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