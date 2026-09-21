<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();
$stmt = $pdo->prepare(
    'SELECT p.*, c.nome AS cliente_nome FROM pedidos p
     INNER JOIN empresas e ON e.id = p.empresa_id
     INNER JOIN clientes c ON c.id = p.cliente_id
     WHERE e.usuario_id = :uid ORDER BY p.created_at DESC'
);
$stmt->execute(['uid' => $_SESSION['usuario_id']]);
$pedidos = $stmt->fetchAll();

$rotulosStatus = [
    'concluido'     => 'concluído',
    'em_andamento'  => 'em andamento',
    'pendente'      => 'pendente',
];

$paginaAtual = 'pedidos';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Pedidos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="content">
            <div class="lista-pedidos" id="listaPedidos">
                <?php foreach ($pedidos as $pedido): ?>
                    <a class="pedido-item" href="pedido_detalhe.php?id=<?= (int)$pedido['id'] ?>">
                        <span class="bolinha"></span>
                        <div class="info">
                            <div class="titulo"><?= htmlspecialchars($pedido['descricao']) ?></div>
                        </div>
                        <div>
                            <div class="status status-<?= htmlspecialchars($pedido['status']) ?>">
                                <?= htmlspecialchars($rotulosStatus[$pedido['status']] ?? $pedido['status']) ?>
                            </div>
                            <div class="data"><?= date('d/m/Y', strtotime($pedido['created_at'])) ?></div>
                        </div>
                        <span class="chevron">&rsaquo;</span>
                    </a>
                <?php endforeach; ?>

                <?php if (!$pedidos): ?>
                    <p class="estado-vazio">Nenhum pedido cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
