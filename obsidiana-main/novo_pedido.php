<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin();

$pdo = getConnection();

$stmtCli = $pdo->prepare(
    'SELECT c.* FROM clientes c
     INNER JOIN empresas e ON e.id = c.empresa_id
     WHERE e.usuario_id = :uid ORDER BY c.nome'
);
$stmtCli->execute(['uid' => $_SESSION['usuario_id']]);
$clientes = $stmtCli->fetchAll();

$stmtEmp = $pdo->prepare('SELECT id, nome FROM empresas WHERE usuario_id = :uid ORDER BY nome');
$stmtEmp->execute(['uid' => $_SESSION['usuario_id']]);
$empresas = $stmtEmp->fetchAll();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId    = (int)($_POST['cliente_id'] ?? 0);
    $empresaId    = (int)($_POST['empresa_id'] ?? 0);
    $descricao    = trim($_POST['descricao'] ?? '');
    $referencias  = trim($_POST['referencias'] ?? '');
    $tipoServico  = trim($_POST['tipo_servico'] ?? '');
    $prazo        = $_POST['prazo'] ?? null;
    $comentario   = trim($_POST['comentario'] ?? '');

    if ($clienteId === 0 || $empresaId === 0 || $descricao === '' || $tipoServico === '') {
        $erro = 'Preencha os campos obrigatórios do pedido.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO pedidos (cliente_id, empresa_id, descricao, referencias, tipo_servico, prazo, comentario, status, created_at)
             VALUES (:cli, :emp, :desc, :ref, :tipo, :prazo, :com, "pendente", NOW())'
        );
        $stmt->execute([
            'cli'   => $clienteId,
            'emp'   => $empresaId,
            'desc'  => $descricao,
            'ref'   => $referencias,
            'tipo'  => $tipoServico,
            'prazo' => $prazo ?: null,
            'com'   => $comentario,
        ]);

        header('Location: pedidos.php');
        exit;
    }
}

$paginaAtual = 'novo_pedido';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deezys - Novo pedido</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-area">
        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="novo-pedido-layout">
            <aside class="clientes-sidebar" style="width:170px;">
                <ul class="lista-clientes" style="margin-top:14px;">
                    <?php foreach ($clientes as $cliente): ?>
                        <li>
                            <label class="cliente-pill" style="cursor:pointer;">
                                <input type="radio" name="cliente_id_visual" style="display:none"
                                       onclick="document.getElementById('cliente_id').value='<?= (int)$cliente['id'] ?>'">
                                <span class="bolinha"></span>
                                <span><?= htmlspecialchars($cliente['nome']) ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="content" style="flex:1;">
                <div class="pedido-form-card">
                    <h2>novo pedido</h2>

                    <?php if ($erro): ?>
                        <div class="form-msg erro"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <form method="post" action="novo_pedido.php">
                        <input type="hidden" id="cliente_id" name="cliente_id"
                               value="<?= isset($clientes[0]) ? (int)$clientes[0]['id'] : '' ?>">

                        <div class="field">
                            <label>Descrição do projeto</label>
                            <textarea name="descricao" required></textarea>
                        </div>

                        <div class="field">
                            <label>Referências</label>
                            <input type="text" name="referencias" placeholder="opcional">
                        </div>

                        <div class="linha-dupla">
                            <div class="field">
                                <label>Tipo de serviço</label>
                                <input type="text" name="tipo_servico" required>
                            </div>
                            <div class="field">
                                <label>Prazo esperado</label>
                                <input type="date" name="prazo">
                            </div>
                        </div>

                        <div class="field">
                            <label>Empresa requerente</label>
                            <select name="empresa_id" required>
                                <option value="">selecione...</option>
                                <?php foreach ($empresas as $empresa): ?>
                                    <option value="<?= (int)$empresa['id'] ?>"><?= htmlspecialchars($empresa['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field">
                            <label>Comentário</label>
                            <textarea name="comentario"></textarea>
                        </div>

                        <div class="pedido-acoes">
                            <button type="button" class="btn-cancelar" onclick="location.href='pedidos.php'">cancelar</button>
                            <button type="submit" class="btn-confirmar">confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>
