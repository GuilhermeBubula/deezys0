<?php
/**
 * sidebar.php
 * Espera opcionalmente a variável $paginaAtual definida antes do include,
 * para destacar o ícone correspondente.
 */
$paginaAtual = $paginaAtual ?? '';
?>
<aside class="sidebar" id="sidebar">
    <button class="sidebar-btn sidebar-toggle" id="btnToggleSidebar" title="Recolher/expandir">
        <span>&lsaquo;</span>
    </button>

    <div class="sidebar-spacer"></div>

    <a class="sidebar-btn <?= $paginaAtual === 'pedidos' ? 'ativo' : '' ?>" href="pedidos.php" title="Pedidos">
        <span>&#128424;</span>
    </a>
    <a class="sidebar-btn <?= $paginaAtual === 'clientes' ? 'ativo' : '' ?>" href="clientes.php" title="Clientes">
        <span>&#128100;</span>
    </a>
    <a class="sidebar-btn <?= $paginaAtual === 'novo_pedido' ? 'ativo' : '' ?>" href="novo_pedido.php" title="Novo pedido">
        <span>&#43;</span>
    </a>
    <a class="sidebar-btn <?= $paginaAtual === 'calendario' ? 'ativo' : '' ?>" href="calendario.php" title="Calendário">
        <span>&#128197;</span>
    </a>
</aside>
