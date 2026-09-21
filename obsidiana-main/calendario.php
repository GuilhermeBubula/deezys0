<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();

// Clientes com contagem de pedidos (badge numérico)
$stmtCli = $pdo->prepare(
    'SELECT c.id, c.nome, COUNT(p.id) AS total_pedidos
     FROM clientes c
     INNER JOIN empresas e ON e.id = c.empresa_id
     LEFT JOIN pedidos p ON p.cliente_id = c.id
     WHERE e.usuario_id = :uid
     GROUP BY c.id, c.nome
     ORDER BY c.nome'
);
$stmtCli->execute(['uid' => $_SESSION['usuario_id']]);
$clientes = $stmtCli->fetchAll();

// Prazos de pedidos (para marcar pontos coloridos no calendário)
$stmtPrazos = $pdo->prepare(
    'SELECT p.prazo, p.status FROM pedidos p
     INNER JOIN empresas e ON e.id = p.empresa_id
     WHERE e.usuario_id = :uid AND p.prazo IS NOT NULL'
);
$stmtPrazos->execute(['uid' => $_SESSION['usuario_id']]);
$prazos = $stmtPrazos->fetchAll();

$coresStatus = ['concluido' => '#3bb54a', 'em_andamento' => '#e8a33d', 'pendente' => '#e05353'];
$eventos = [];
foreach ($prazos as $p) {
    $eventos[$p['prazo']] = $coresStatus[$p['status']] ?? '#7c818a';
}

$paginaAtual = 'calendario';
$mesAtual = (int)date('n') - 1;
$anoAtual = (int)date('Y');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Calendário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="content">
            <div class="busca-calendario">
                <span>&#128269;</span>
                <input type="text" placeholder="Buscar cliente ou pedido..." data-filtro-lista="#listaClientesCalendario li">
            </div>

            <div class="calendario-layout">
                <aside class="calendario-sidebar">
                    <ul class="lista-clientes" id="listaClientesCalendario" style="padding:0;">
                        <?php foreach ($clientes as $cliente): ?>
                            <li>
                                <div class="cliente-pill">
                                    <span class="bolinha"></span>
                                    <span><?= htmlspecialchars($cliente['nome']) ?></span>
                                    <span class="badge-numero"><?= (int)$cliente['total_pedidos'] ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        <?php if (!$clientes): ?>
                            <li class="estado-vazio" style="color:#eee;">Nenhum cliente cadastrado.</li>
                        <?php endif; ?>
                    </ul>
                </aside>

                <section class="calendario-principal">
                    <div class="calendario-card">
                        <div class="calendario-header">
                            <button id="btnMesAnterior" type="button">&lsaquo;</button>
                            <select id="selectMes">
                                <?php
                                $meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                                foreach ($meses as $i => $nomeMes):
                                ?>
                                    <option value="<?= $i ?>" <?= $i === $mesAtual ? 'selected' : '' ?>><?= $nomeMes ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="selectAno">
                                <?php for ($a = $anoAtual - 3; $a <= $anoAtual + 3; $a++): ?>
                                    <option value="<?= $a ?>" <?= $a === $anoAtual ? 'selected' : '' ?>><?= $a ?></option>
                                <?php endfor; ?>
                            </select>
                            <button id="btnMesProximo" type="button">&rsaquo;</button>
                        </div>

                        <div class="grade-calendario" id="gradeCalendario"
                             data-eventos='<?= htmlspecialchars(json_encode($eventos), ENT_QUOTES) ?>'>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
