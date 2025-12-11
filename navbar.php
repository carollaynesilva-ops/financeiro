<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="index.php">Financeiro</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="categorias_listar.php">Categorias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="transacoes_listar.php">Transações</a>
            </li>
        </ul>

        <span class="navbar-text me-3">
            🤖Usuário: <?php echo $usuario_nome ?>
        </span>

        <a class="nav-link" href="logout.php" style="color:#c45a89;">Sair</a>
    </div>
</nav>
