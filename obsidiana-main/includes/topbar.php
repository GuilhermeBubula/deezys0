<?php $usuario = usuarioAtual(); ?>
<div class="topbar">
    <div class="avatar-wrapper" id="avatarWrapper">
        <img class="avatar" src="https://i.pravatar.cc/60" alt="Avatar do usuário">
        <div class="avatar-menu" id="avatarMenu">
            <p class="avatar-nome"><?= htmlspecialchars($usuario['nome_usuario'] ?? 'Usuário') ?></p>
            <a href="empresas.php">Minhas empresas</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
</div>
