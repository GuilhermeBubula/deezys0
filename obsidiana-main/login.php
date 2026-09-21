<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (isLogado()) {
    header('Location: empresas.php');
    exit;
}

$erro = '';
$sucesso = isset($_GET['cadastro']) ? 'Cadastro realizado com sucesso! Faça login para continuar.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario === '' || $senha === '') {
        $erro = 'Informe usuário e senha.';
    } else {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE nome_usuario = :u1 OR email = :u2 LIMIT 1');
        $stmt->execute(['u1' => $usuario, 'u2' => $usuario]);
        $dados = $stmt->fetch();

        if ($dados && password_verify($senha, $dados['senha_hash'])) {
            $_SESSION['usuario_id'] = $dados['id'];
            $_SESSION['usuario'] = $dados;
            header('Location: empresas.php');
            exit;
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <img class="avatar-decor" src="https://i.pravatar.cc/60?img=5" alt="avatar">
        <h1 class="auth-title">Deezys</h1>

        <?php if ($erro): ?>
            <div class="form-msg erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="form-msg sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="field">
                <label for="usuario">Username</label>
                <input type="text" id="usuario" name="usuario" placeholder="Username..." required>
            </div>

            <div class="field">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha..." required>
            </div>

            <p class="auth-links" style="text-align:left; margin: -8px 0 16px;">
                <a href="recuperar_senha.php">esqueceu a senha?</a>
            </p>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="auth-links">ainda não possui conta? <a href="cadastro.php">clique aqui</a></p>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
