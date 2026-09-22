<?php
session_start();
require_once 'db.php';

// Verificar se o utilizador está em sessão e é Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'admin') {
    header("Location: catalog.php");
    exit;
}

$msg = '';
$msg_tipo = '';

// 1. Adicionar Filme
if (isset($_POST['add_filme'])) {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $link = $conn->real_escape_string($_POST['link_youtube']);
    
    $imagem_nome = $_FILES['imagem']['name'];
    $imagem_tmp = $_FILES['imagem']['tmp_name'];
    $ext = strtolower(pathinfo($imagem_nome, PATHINFO_EXTENSION));
    
    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $novo_nome = uniqid() . '.' . $ext;
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        
        if (move_uploaded_file($imagem_tmp, 'uploads/' . $novo_nome)) {
            $sql = "INSERT INTO filmes (titulo, descricao, categoria, imagem, link_youtube) VALUES ('$titulo', '$descricao', '$categoria', '$novo_nome', '$link')";
            if ($conn->query($sql)) {
                $msg = "Filme adicionado com sucesso ao catálogo!";
                $msg_tipo = "sucesso";
            } else {
                $msg = "Erro ao guardar na base de dados: " . $conn->error;
                $msg_tipo = "erro";
            }
        }
    } else {
        $msg = "Formato de imagem inválido! Apenas PNG, JPG e JPEG são suportados.";
        $msg_tipo = "erro";
    }
}

// 2. Editar Filme
if (isset($_POST['edit_filme'])) {
    $id = (int)$_POST['id_filme'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $link = $conn->real_escape_string($_POST['link_youtube']);
    
    // Atualização base de dados sem trocar imagem
    $sql = "UPDATE filmes SET titulo='$titulo', descricao='$descricao', categoria='$categoria', link_youtube='$link' WHERE id=$id";
    $conn->query($sql);

    // Se tiver sido enviada uma nova imagem
    if (!empty($_FILES['imagem']['name'])) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $ext = strtolower(pathinfo($imagem_nome, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $novo_nome = uniqid() . '.' . $ext;
            if (move_uploaded_file($imagem_tmp, 'uploads/' . $novo_nome)) {
                $conn->query("UPDATE filmes SET imagem='$novo_nome' WHERE id=$id");
            }
        }
    }

    $msg = "Filme atualizado com sucesso!";
    $msg_tipo = "sucesso";
}

// 3. Remover Filme
if (isset($_GET['del_filme'])) {
    $id = (int)$_GET['del_filme'];
    $conn->query("DELETE FROM filmes WHERE id = $id");
    header("Location: admin.php");
    exit;
}

// 4. Promover ou Revogar Permissão de Admin
if (isset($_GET['toggle_user'])) {
    $id = (int)$_GET['toggle_user'];
    $tipo_atual = $_GET['tipo'];
    $novo_tipo = ($tipo_atual == 'admin') ? 'normal' : 'admin';
    $conn->query("UPDATE utilizadores SET tipo = '$novo_tipo' WHERE id = $id");
    header("Location: admin.php");
    exit;
}

// Obter Estatísticas Gerais do Site
$total_users = $conn->query("SELECT COUNT(*) as total FROM utilizadores")->fetch_assoc()['total'];
$total_filmes = $conn->query("SELECT COUNT(*) as total FROM filmes")->fetch_assoc()['total'];
$total_views = $conn->query("SELECT COUNT(*) as total FROM historico")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>Painel de Administração - RodeiaFlix</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <header>
        <div class="logo">RodeiaFlix Admin</div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="catalog.php">Catálogo</a></li>
                <li><a href="about.php">Sobre</a></li>
                <li><a href="logout.php" class="btn-primary">Sair</a></li>
            </ul>
        </nav>
    </header>

    <section class="content-section" style="margin-top: 100px; max-width: 1200px; margin-left: auto; margin-right: auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="font-size: 2.5rem; font-weight: 800;">Painel de Controlo</h1>
            <span style="background: rgba(229,9,20,0.2); color: var(--primary-color); padding: 8px 16px; border-radius: 20px; font-weight: 600; border: 1px solid var(--primary-color);">
                Sessão de Admin: <?= htmlspecialchars($_SESSION['user_nome']) ?>
            </span>
        </div>

        <?php if ($msg): ?>
            <div style="padding: 15px; border-radius: var(--radius); margin-bottom: 30px; font-weight: 500; <?= $msg_tipo == 'sucesso' ? 'background: rgba(46,204,113,0.15); color: #2ecc71; border: 1px solid #2ecc71;' : 'background: rgba(229,9,20,0.15); color: #E50914; border: 1px solid #E50914;' ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- Estatísticas -->
        <h2 class="section-title">Estatísticas Gerais</h2>
        <div class="grid-movies" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 50px;">
            <div class="movie-card" style="padding: 25px; border: 1px solid var(--border-color);">
                <span style="font-size: 0.9rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px;">Utilizadores Registados</span>
                <h2 style="font-size: 3rem; font-weight: 800; color: var(--text-white); margin-top: 10px;"><?= $total_users ?></h2>
            </div>
            <div class="movie-card" style="padding: 25px; border: 1px solid var(--border-color);">
                <span style="font-size: 0.9rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px;">Filmes no Catálogo</span>
                <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-top: 10px;"><?= $total_filmes ?></h2>
            </div>
            <div class="movie-card" style="padding: 25px; border: 1px solid var(--border-color);">
                <span style="font-size: 0.9rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px;">Visualizações Totais</span>
                <h2 style="font-size: 3rem; font-weight: 800; color: #3498db; margin-top: 10px;"><?= $total_views ?></h2>
            </div>
        </div>

        <!-- Formulário Adicionar Filme -->
        <div style="background: var(--bg-card); padding: 35px; border-radius: var(--radius); border: 1px solid var(--border-color); margin-bottom: 50px;">
            <h2 class="section-title" style="margin-bottom: 25px;">Adicionar Novo Filme</h2>
            <form method="POST" action="admin.php" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Título do Filme</label>
                        <input type="text" name="titulo" placeholder="Ex: Interstellar" required>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Categoria</label>
                        <input type="text" name="categoria" placeholder="Ex: Ficção Científica, Ação" required>
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Sinopse / Descrição</label>
                    <textarea name="descricao" placeholder="Escreva um resumo do filme..." required rows="3"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Link Normal do YouTube</label>
                        <input type="url" name="link_youtube" placeholder="https://www.youtube.com/watch?v=..." required>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Capa do Filme (PNG, JPG, JPEG)</label>
                        <input type="file" name="imagem" accept="image/png, image/jpeg, image/jpg" required style="padding: 12px; background: #222;">
                    </div>
                </div>

                <button type="submit" name="add_filme" class="btn-primary" style="padding: 14px 30px; margin-top: 10px;">
                    + Guardar Filme
                </button>
            </form>
        </div>

        <!-- Tabela de Filmes -->
        <h2 class="section-title">Gerir Catálogo de Filmes</h2>
        <div style="overflow-x: auto; margin-bottom: 50px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Capa</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Link YouTube</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM filmes ORDER BY id DESC");
                    while($f = $res->fetch_assoc()):
                        $img = file_exists('uploads/' . $f['imagem']) ? 'uploads/' . $f['imagem'] : 'https://via.placeholder.com/150x225';
                        $f_json = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td style="width: 70px;">
                            <img src="<?= $img ?>" alt="Capa" style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><strong><?= htmlspecialchars($f['titulo']) ?></strong></td>
                        <td><span style="background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem;"><?= htmlspecialchars($f['categoria']) ?></span></td>
                        <td><a href="<?= htmlspecialchars($f['link_youtube']) ?>" target="_blank" style="color: #3498db; text-decoration: underline;">Ver no YouTube</a></td>
                        <td style="display: flex; gap: 10px; align-items: center; padding-top: 30px;">
                            <button onclick='abrirModalEditar(<?= $f_json ?>)' class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">Editar</button>
                            <a href="admin.php?del_filme=<?= $f['id'] ?>" class="btn-primary" style="background: #e74c3c; padding: 6px 12px; font-size: 0.85rem;" onclick="return confirm('Tem a certeza que deseja eliminar este filme?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabela de Utilizadores -->
        <h2 class="section-title">Gestão de Permissões de Utilizadores</h2>
        <div style="overflow-x: auto; margin-bottom: 50px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Ações de Permissão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $conn->query("SELECT * FROM utilizadores ORDER BY id ASC");
                    while($u = $users->fetch_assoc()):
                    ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><strong><?= htmlspecialchars($u['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; <?= $u['tipo'] == 'admin' ? 'background: rgba(229,9,20,0.2); color: var(--primary-color); border: 1px solid var(--primary-color);' : 'background: rgba(255,255,255,0.1); color: #fff;' ?>">
                                <?= strtoupper($u['tipo']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="admin.php?toggle_user=<?= $u['id'] ?>&tipo=<?= $u['tipo'] ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">
                                    <?= ($u['tipo'] == 'admin') ? 'Revogar Admin' : 'Promover a Admin' ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-gray); font-style: italic; font-size: 0.85rem;">Sua conta ativa</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </section>

    <!-- Modal de Edição de Filme -->
    <div id="modalEditar" class="modal-video">
        <div class="modal-content" style="aspect-ratio: auto; background: var(--bg-card); padding: 35px; max-width: 700px; border: 1px solid var(--border-color);">
            <span class="close-btn" onclick="fecharModalEditar()">&times;</span>
            <h2 class="section-title" style="margin-bottom: 25px;">Editar Filme</h2>
            <form method="POST" action="admin.php" enctype="multipart/form-data">
                <input type="hidden" name="id_filme" id="edit_id">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Título</label>
                        <input type="text" name="titulo" id="edit_titulo" required>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Categoria</label>
                        <input type="text" name="categoria" id="edit_categoria" required>
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Sinopse / Descrição</label>
                    <textarea name="descricao" id="edit_descricao" required rows="3"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Link do YouTube</label>
                        <input type="url" name="link_youtube" id="edit_link" required>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: var(--text-gray);">Nova Capa (Opcional)</label>
                        <input type="file" name="imagem" accept="image/png, image/jpeg, image/jpg" style="padding: 12px; background: #222;">
                    </div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <button type="submit" name="edit_filme" class="btn-primary" style="padding: 12px 25px;">Guardar Alterações</button>
                    <button type="button" onclick="fecharModalEditar()" class="btn-secondary" style="padding: 12px 25px;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> RodeiaFlix. Todos os direitos reservados.</p>
        <p>Site desenvolvido por <strong>Marco Rodeia</strong> | Tecnologias: HTML5, CSS3, JavaScript, PHP e MySQL</p>
    </footer>

    <script src="script.js"></script>
    <script>
        function abrirModalEditar(filme) {
            document.getElementById('edit_id').value = filme.id;
            document.getElementById('edit_titulo').value = filme.titulo;
            document.getElementById('edit_categoria').value = filme.categoria;
            document.getElementById('edit_descricao').value = filme.descricao;
            document.getElementById('edit_link').value = filme.link_youtube;
            
            const modal = document.getElementById('modalEditar');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function fecharModalEditar() {
            const modal = document.getElementById('modalEditar');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>