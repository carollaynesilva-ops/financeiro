<?php
require_once 'config.php';
require_once 'mensagens.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

$sql = "SELECT * FROM categoria WHERE id_usuario = :usuario_id ORDER BY tipo, nome";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$categorias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categorias - Sistema Financeiro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #fdeff5;
    }

    .navbar {
        background: #fff6fa !important;
        border-bottom: 1px solid #f5d3e0;
    }

    .navbar-brand,
    .nav-link {
        color: #c45a89 !important;
        font-weight: 600;
    }

    .nav-link.active {
        background: #fbe2ef;
        border-radius: 8px;
    }

    .main-box {
        background: #ffffffdd;
        border-radius: 20px;
        border: 1px solid #f5d3e0;
        padding: 30px;
        margin-top: 30px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    h2 {
        color: #c45a89;
        font-weight: 700;
    }

    .btn-primary {
        background-color: #e9a8c9;
        border-color: #e9a8c9;
    }

    .btn-primary:hover {
        background-color: #d488b1;
        border-color: #d488b1;
    }

    .btn-success {
        background-color: #b49dee;
        border-color: #b49dee;
    }

    .btn-success:hover {
        background-color: #9a83d3;
        border-color: #9a83d3;
    }

    .btn-danger {
        background-color: #f58ba5;
        border-color: #f58ba5;
    }

    .btn-danger:hover {
        background-color: #d96f89;
        border-color: #d96f89;
    }

    table thead {
        background: #e58dbb;
        color: white;
    }

    table tbody tr:hover {
        background: #fde8f1;
    }
</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
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
                Usuário: <?php echo $usuario_nome ?>
            </span>

            <a class="nav-link" href="logout.php" style="color:#c45a89;">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="main-box">

        <?php exibir_mensagem(); ?>

        <h2>Minhas Categorias</h2>

        <a class="btn btn-primary mb-3" href="categorias_formulario.php">+ Nova Categoria</a>

        <?php if (count($categorias) > 0): ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th style="width: 160px;">Ações</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($categorias as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= ucfirst($c['tipo']) ?></td>
                        <td>
                            <a class="btn btn-success btn-sm" 
                               href="categorias_formulario.php?id=<?= $c['id_categoria'] ?>">
                                Editar
                            </a>

                            <a class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir?')"
                               href="categorias_excluir.php?id=<?= $c['id_categoria'] ?>">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p>Nenhuma categoria cadastrada ainda.</p>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
