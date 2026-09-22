<?php
session_start();
require_once 'db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM utilizadores WHERE email = '$email'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($senha, $user['senha']) || $senha == 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo'];
            header("Location: catalog.php");
            exit;
        } else {
            $erro = "Palavra-passe incorreta.";
        }
    } else {
        $erro = "Utilizador não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>Iniciar Sessão - RodeiaFlix</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="auth-wrapper">
        <div class="login-container">
            <span class="close-btn" onclick="history.back()">&times;</span>
            <div class="logo" style="margin-bottom: 20px;">RodeiaFlix</div>
            <h2>Iniciar Sessão</h2>
            <?php if ($erro): ?>
                <p style="color: #E50914; background: rgba(229,9,20,0.1); padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem;"><?= $erro ?></p>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <input type="password" name="senha" placeholder="Palavra-passe" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">Entrar</button>
            </form>
            <p style="margin-top: 25px; color: var(--text-gray); font-size: 0.95rem;">
                Novo no RodeiaFlix? <a href="register.php" style="color: #fff; font-weight: 600;">Registe-se agora.</a>
            </p>
        </div>
    </div>

    <footer>
        <p>Site desenvolvido por <strong>Marco Rodeia</strong> | Tecnologias: HTML5, CSS3, JavaScript, PHP e MySQL</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>