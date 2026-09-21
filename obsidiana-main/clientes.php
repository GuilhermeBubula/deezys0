<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();
$stmt = $pdo->prepare(
    'SELECT c.* FROM clientes c
     INNER JOIN empresas e ON e.id = c.empresa_id
     WHERE e.usuario_id = :uid ORDER BY c.nome ASC'
);
$stmt->execute(['uid' => $_SESSION['usuario_id']]);
$clientes = $stmt->fetchAll();

$paginaAtual = 'clientes';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Clientes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="clientes-layout">
            <aside class="clientes-sidebar">
                <button class="btn-add-cliente" onclick="location.href='cliente_novo.php'">adicionar cliente</button>
                <ul class="lista-clientes" id="listaClientes">
                    <?php foreach ($clientes as $cliente): ?>
                        <li>
                            <button class="cliente-pill" data-nome="<?= htmlspecialchars($cliente['nome']) ?>">
                                <span class="bolinha"></span>
                                <span><?= htmlspecialchars($cliente['nome']) ?></span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                    <?php if (!$clientes): ?>
                        <li><button class="cliente-pill" data-nome='"cliente"'><span class="bolinha"></span><span>"cliente"</span></button></li>
                        <li><button class="cliente-pill" data-nome='"cliente"'><span class="bolinha"></span><span>"cliente"</span></button></li>
                        <li><button class="cliente-pill" data-nome='"cliente"'><span class="bolinha"></span><span>"cliente"</span></button></li>
                    <?php endif; ?>
                </ul>
            </aside>

            <section class="clientes-conteudo">
                <div class="busca-topo">
                    <input type="text" placeholder="Cliente" data-filtro-lista="#listaClientes li">
                    <span>&#128269;</span>
                </div>

                <div id="detalheCliente">
                    <p class="estado-vazio">Selecione um cliente na lista ao lado para ver os detalhes.</p>
                </div>
            </section>
        </div>

        <div class="paginacao-clientes">
            <div class="barra"></div>
            <button>&rsaquo;</button>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
