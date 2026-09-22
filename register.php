<?php
session_start();
require_once 'db.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar se o e-mail já existe
    $check = $conn->query("SELECT id FROM utilizadores WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $erro = "O e-mail introduzido já se encontra registado!";
    } else {
        // Inserir novo utilizador (tipo 'normal' por defeito)
        $sql = "INSERT INTO utilizadores (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', 'normal')";
        if ($conn->query($sql)) {
            $sucesso = "Conta criada com sucesso! Já pode iniciar sessão.";
        } else {
            $erro = "Erro ao criar conta: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marco Rodeia">
    <title>Criar Conta - RodeiaFlix</title>
    <link rel="icon" href="./icon/rodeiaflix.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="auth-wrapper">
        <div class="login-container">
            <!-- Botão "X" para voltar atrás -->
            <span class="close-btn" onclick="history.back()">&times;</span>
            
            <div class="logo" style="margin-bottom: 20px;">RodeiaFlix</div>
            <h2>Criar Conta</h2>
            
            <?php if ($erro): ?>
                <p style="color: #E50914; background: rgba(229,9,20,0.1); padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid rgba(229,9,20,0.3);"><?= $erro ?></p>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <p style="color: #2ecc71; background: rgba(46,204,113,0.1); padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid rgba(46,204,113,0.3);"><?= $sucesso ?></p>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="form-group">
                    <input type="text" name="nome" placeholder="Nome Completo" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Endereço de E-mail" required>
                </div>
                <div class="form-group">
                    <input type="password" name="senha" placeholder="Palavra-passe" required minlength="6">
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">Registar</button>
            </form>

            <p style="margin-top: 25px; color: var(--text-gray); font-size: 0.95rem;">
                Já tem uma conta? <a href="login.php" style="color: #fff; font-weight: 600;">Inicie sessão aqui.</a>
            </p>
        </div>
    </div>

    <footer>
        <p>Site desenvolvido por <strong>Marco Rodeia</strong> | Tecnologias: HTML5, CSS3, JavaScript, PHP e MySQL</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>