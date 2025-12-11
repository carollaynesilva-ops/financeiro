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

// Verificar se está editando
$id_transacao = $_GET['id'] ?? null;
$transacao = null;

if ($id_transacao) {
    // Buscar transação para editar
    $sql = "SELECT * FROM transacao WHERE id_transacao = :id_transacao AND id_usuario = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_transacao', $id_transacao);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $transacao = $stmt->fetch();

    // Se não encontrou ou não pertence ao usuário, redireciona
    if (!$transacao) {
        set_mensagem('Transação não encontrada.', 'erro');
        header('Location: transacoes_listar.php');
        exit;
    }
}

// Buscar categorias do usuário
$sql_categorias = "SELECT * FROM categoria WHERE id_usuario = :usuario_id ORDER BY tipo, nome";
$stmt_categorias = $conn->prepare($sql_categorias);
$stmt_categorias->bindParam(':usuario_id', $usuario_id);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $transacao ? 'Editar' : 'Nova'; ?> Transação - Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fdeff5;
            font-family: 'Poppins', sans-serif;
            color: #6c2a4b;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* topo */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
        }

        .top-bar h1 {
            margin: 0;
            color: #c45a89;
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .logout {
            color: #a03e6e;
            font-weight: 600;
            text-decoration: none;
        }

        /* menu */
        .menu {
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }

        .menu a {
            text-decoration: none;
            font-weight: 600;
            color: #c45a89;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        /* card */
        .card {
            background: #ffffffdd;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            color: #a03e6e;
            font-size: 1.5rem;
        }

        /* formulário */
        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        input,
        select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #e3b7cb;
            background: #fff6fa;
            font-size: 1rem;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #c45a89;
        }

        /* botões */
        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: .25s;
        }

        .btn.salvar {
            background: linear-gradient(135deg, #c45a89, #a03e6e);
            color: white;
            border: none;
        }

        .btn.salvar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn.cancelar {
            background: #f5d3e0;
            color: #6c2a4b;
        }

        .btn.cancelar:hover {
            background: #e8bfd0;
        }

        .acoes {
            display: flex;
            gap: 15px;
        }

        /* Navbar customizado rosa */
        .navbar {
            background: #fff6fa !important;
            /* fundo rosa clarinho */
            border-bottom: 1px solid #f5d3e0;
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            color: #c45a89 !important;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .navbar-nav {
            display: flex;
            gap: 15px;
            /* espaçamento entre links */
        }

        .nav-item .nav-link {
            color: #c45a89 !important;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .nav-item .nav-link.active,
        .nav-item .nav-link:hover {
            background: #fbe2ef;
            color: #a23070 !important;
        }

        /* Botão de logout dentro do nav */
        .navbar .btn-sair {
            background: #d75791;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .navbar .btn-sair:hover {
            background: #c64580;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">

        <header class="top-bar">
            <h1>Sistema Financeiro Pessoal</h1>
            <div class="user-info">
                <span>Bem-vindo, <strong><?php echo htmlspecialchars($usuario_nome); ?></strong></span>
                <a class="logout" href="logout.php">Sair</a>
            </div>
        </header>

        <?php exibir_mensagem(); ?>


        <div class="card">
            <h2><?php echo $transacao ? 'Editar' : 'Nova'; ?> Transação</h2>

            <?php if (count($categorias) === 0): ?>
                <p><strong>Atenção:</strong> Cadastre uma categoria antes de criar transações.</p>
                <a class="btn" href="categorias_formulario.php">Cadastrar Categoria</a>

            <?php else: ?>
                <form action="transacoes_salvar.php" method="POST" class="form">

                    <?php if ($transacao): ?>
                        <input type="hidden" name="id_transacao" value="<?php echo $transacao['id_transacao']; ?>">
                    <?php endif; ?>

                    <div class="campo">
                        <label for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao"
                            value="<?php echo $transacao ? htmlspecialchars($transacao['descricao']) : ''; ?>" required>
                    </div>

                    <div class="campo">
                        <label for="valor">Valor</label>
                        <input type="number" id="valor" name="valor" step="0.01" min="0.01"
                            value="<?php echo $transacao ? number_format($transacao['valor'], 2, '.', '') : ''; ?>" required>
                    </div>

                    <div class="campo">
                        <label for="data_transacao">Data</label>
                        <input type="date" id="data_transacao" name="data_transacao"
                            value="<?php echo $transacao ? $transacao['data_transacao'] : date('Y-m-d'); ?>" required>
                    </div>

                    <div class="campo">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="receita" <?php if ($transacao && $transacao['tipo'] == 'receita') echo 'selected'; ?>>Receita</option>
                            <option value="despesa" <?php if ($transacao && $transacao['tipo'] == 'despesa') echo 'selected'; ?>>Despesa</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="id_categoria">Categoria</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id_categoria']; ?>"
                                    <?php echo ($transacao && $transacao['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria['nome']); ?> (<?php echo ucfirst($categoria['tipo']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="acoes">
                        <button class="btn salvar" type="submit">Salvar</button>
                        <a class="btn cancelar" href="transacoes_listar.php">Cancelar</a>
                    </div>

                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>