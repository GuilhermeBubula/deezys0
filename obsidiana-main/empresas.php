<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();
$stmt = $pdo->prepare('SELECT * FROM empresas WHERE usuario_id = :uid ORDER BY id ASC');
$stmt->execute(['uid' => $_SESSION['usuario_id']]);
$empresas = $stmt->fetchAll();

$paginaAtual = 'empresas';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Empresas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="content">
            <div class="empresas-wrap">
                <div class="empresas-painel">
                    <h2>Empresas</h2>
                    <div class="empresas-lista">
                        <?php foreach ($empresas as $empresa): ?>
                            <a class="empresa-card" href="empresa_editar.php?id=<?= (int)$empresa['id'] ?>">
                                <span class="config-icon">&#9881;</span>
                                <span class="icone-circulo">&#128100;</span>
                                <span class="nome"><?= htmlspecialchars($empresa['nome']) ?></span>
                            </a>
                        <?php endforeach; ?>

                        <a class="empresa-card adicionar" href="nova_empresa.php">
                            <span class="icone-circulo">&#43;</span>
                            <span class="nome">Adicionar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
