<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeUsuario   = trim($_POST['nome_usuario'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $senha         = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($nomeUsuario === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } elseif ($senha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
        try {
            $pdo = getConnection();

            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE nome_usuario = :u OR email = :e');
            $stmt->execute(['u' => $nomeUsuario, 'e' => $email]);

            if ($stmt->fetch()) {
                $erro = 'Já existe um usuário com este nome ou e-mail.';
            } else {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO usuarios (nome_usuario, email, senha_hash, created_at) VALUES (:u, :e, :s, NOW())');
                $stmt->execute(['u' => $nomeUsuario, 'e' => $email, 's' => $hash]);

                header('Location: login.php?cadastro=1');
                exit;
            }
        } catch (Exception $ex) {
            $erro = 'Erro ao cadastrar. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <img class="avatar-decor" src="https://i.pravatar.cc/60?img=12" alt="avatar">
        <h1 class="auth-title">Deezys</h1>

        <?php if ($erro): ?>
            <div class="form-msg erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form id="formCadastro" method="post" action="cadastro.php">
            <div class="field">
                <label for="nome_usuario">Nome de usuário</label>
                <input type="text" id="nome_usuario" name="nome_usuario" placeholder="Nome de usuário..."
                       value="<?= htmlspecialchars($_POST['nome_usuario'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email..."
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha..." required>
            </div>

            <div class="field">
                <label for="confirmar_senha">Confirmar a senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar a senha..." required>
            </div>

            <button type="submit" class="btn-primary">Cadastrar</button>
        </form>

        <p class="auth-links">Já possui conta? <a href="login.php">clique aqui</a></p>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
