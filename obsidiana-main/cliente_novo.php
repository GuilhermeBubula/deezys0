<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();
$stmtEmp = $pdo->prepare('SELECT id, nome FROM empresas WHERE usuario_id = :uid ORDER BY nome');
$stmtEmp->execute(['uid' => $_SESSION['usuario_id']]);
$empresas = $stmtEmp->fetchAll();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $empresaId = (int)($_POST['empresa_id'] ?? 0);

    if ($nome === '' || $empresaId === 0) {
        $erro = 'Preencha o nome e selecione a empresa.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO clientes (empresa_id, nome, created_at) VALUES (:e, :n, NOW())');
        $stmt->execute(['e' => $empresaId, 'n' => $nome]);
        header('Location: clientes.php');
        exit;
    }
}

$paginaAtual = 'clientes';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Novo cliente</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>
        <div class="content">
            <div class="painel-form">
                <h2>novo cliente</h2>
                <?php if ($erro): ?><div class="form-msg erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
                <form method="post" action="cliente_novo.php">
                    <div class="field">
                        <label>nome do cliente</label>
                        <input type="text" name="nome" required>
                    </div>
                    <div class="field">
                        <label>empresa</label>
                        <select name="empresa_id" required>
                            <option value="">selecione...</option>
                            <?php foreach ($empresas as $e): ?>
                                <option value="<?= (int)$e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
