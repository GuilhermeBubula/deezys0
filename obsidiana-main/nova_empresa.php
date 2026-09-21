<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $cnpj     = trim($_POST['cnpj'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $setor    = trim($_POST['setor'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome === '' || $cnpj === '' || $email === '' || $setor === '') {
        $erro = 'Preencha os campos obrigatórios.';
    } else {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO empresas (usuario_id, nome, cnpj, email, setor, endereco, created_at)
             VALUES (:uid, :nome, :cnpj, :email, :setor, :endereco, NOW())'
        );
        $stmt->execute([
            'uid'      => $_SESSION['usuario_id'],
            'nome'     => $nome,
            'cnpj'     => $cnpj,
            'email'    => $email,
            'setor'    => $setor,
            'endereco' => $endereco,
        ]);

        header('Location: empresas.php');
        exit;
    }
}

$paginaAtual = 'empresas';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Nova empresa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="content">
            <div class="painel-form">
                <h2>nova empresa</h2>

                <?php if ($erro): ?>
                    <div class="form-msg erro"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form method="post" action="nova_empresa.php">
                    <div class="field">
                        <label>nome da empresa</label>
                        <input type="text" name="nome" required>
                    </div>
                    <div class="field">
                        <label>CNPJ</label>
                        <input type="text" name="cnpj" required>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="field">
                        <label>setor</label>
                        <input type="text" name="setor" required>
                    </div>
                    <div class="field">
                        <label>endereço</label>
                        <input type="text" name="endereco" placeholder="opcional">
                        <small>opcional</small>
                    </div>

                    <button type="submit" class="btn-primary">adicionar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
