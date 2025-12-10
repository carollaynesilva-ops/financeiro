<?php
require_once 'config.php';
require_once 'mensagens.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

// Buscar todas as categorias do usuário
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background: #fce5ef;
            /* rosa suave de fundo */
        }

        /* Navbar fofinha */
        .navbar {
            background-color: #ffe6f1 !important;
            border-bottom: 1px solid #f7c8dc;
        }

        .navbar-brand,
        .nav-link {
            color: #d81b60 !important;
            font-weight: 600;
        }

        .nav-link.active {
            background: #fcd4e6;
            border-radius: 10px;
        }

        /* Container com leve destaque */
        .container {
            background: #ffffffee;
            padding: 30px;
            margin-top: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
            border: 1px solid #f7c8dc;
        }

        h2 {
            color: #d81b60;
            margin-bottom: 20px;
            font-weight: 700;
        }

        /* Botões */
        .btn-primary {
            background-color: #d81b60;
            border-color: #d81b60;
        }

        .btn-primary:hover {
            background-color: #a11449;
            border-color: #a11449;
        }

        .btn-success {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }

        .btn-success:hover {
            background-color: #4b36ad;
            border-color: #4b36ad;
        }

        .btn-danger {
            background-color: #e63946;
            border-color: #e63946;
        }

        .btn-danger:hover {
            background-color: #b31f2a;
            border-color: #b31f2a;
        }

        /* Tabela */
        table thead {
            background: #d81b60;
            color: white;
        }

        table tbody tr:nth-child(even) {
            background: #ffeef6;
        }

        table tbody tr:hover {
            background: #fde3ef;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Financeiro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="categorias_listar.php">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transacoes_listar.php">Transações</a>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                    <a class="nav-link">🤖Usuário: <?php echo $usuario_nome ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">

        <?php exibir_mensagem(); ?>

        <h2>Categorias</h2>

        <div>
            <a class="btn btn-primary" href="categorias_formulario.php">Nova Categoria</a>
        </div>

        <?php if (count($categorias) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                            <td><?php echo ucfirst($categoria['tipo']); ?></td>
                            <td>
                                <a class="btn btn-success" href="categorias_formulario.php?id=<?php echo $categoria['id_categoria']; ?>">Editar</a>
                                <a class="btn btn-danger" href="categorias_excluir.php?id=<?php echo $categoria['id_categoria']; ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma categoria cadastrada ainda.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>