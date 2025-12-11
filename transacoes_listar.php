<?php
require_once 'config.php';
require_once 'mensagens.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

$filtro_tipo = $_GET['tipo'] ?? '';
$filtro_categoria = $_GET['categoria'] ?? '';

$sql = "SELECT t.*, c.nome as categoria_nome 
        FROM transacao t 
        LEFT JOIN categoria c ON t.id_categoria = c.id_categoria 
        WHERE t.id_usuario = :usuario_id";

$params = [':usuario_id' => $usuario_id];

if ($filtro_tipo && in_array($filtro_tipo, ['receita', 'despesa'])) {
    $sql .= " AND t.tipo = :tipo";
    $params[':tipo'] = $filtro_tipo;
}

if ($filtro_categoria) {
    $sql .= " AND t.id_categoria = :categoria";
    $params[':categoria'] = $filtro_categoria;
}

$sql .= " ORDER BY t.data_transacao DESC, t.id_transacao DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$transacoes = $stmt->fetchAll();

$sql_categorias = "SELECT * FROM categoria WHERE id_usuario = :usuario_id ORDER BY nome";
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
    <title>Transações - Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Reset e fontes */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background: #fdeff5;
            color: #333;
            padding: 20px;
        }

        /* Cabeçalhos */
        h1,
        h2,
        h3 {
            color: #c45a89;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* Links */
        a {
            color: #c45a89;
            text-decoration: none;
            transition: 0.2s;
        }

        a:hover {
            color: #a03e6e;
        }

        /* Navbar */
        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        nav ul li a {
            padding: 8px 12px;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 600;
        }

        nav ul li a:hover {
            background: #fbe2ef;
        }

        /* Bem-vindo */
        .welcome {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff6fa;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .welcome strong {
            color: #c45a89;
        }

        /* Formulário filtros */
        form {
            background: #fff6fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #b54f82;
        }

        select,
        input,
        button {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #f5d3e0;
            outline: none;
            width: 100%;
        }

        button {
            background: linear-gradient(120deg, #c45a89, #a03e6e);
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(120deg, #a03e6e, #c45a89);
        }

        /* Tabela transações */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            background: #fff6fa;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
        }

        th {
            background: #fbe2ef;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #fde4f1;
        }

        tr:hover {
            background: #fcdff0;
            transition: 0.2s;
        }

        /* Ações */
        .actions a {
            margin-right: 10px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .actions a:hover {
            color: #a03e6e;
        }

        /* Botão nova transação */
        .new-transaction {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(120deg, #c45a89, #a03e6e);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            transition: 0.3s;
        }

        .new-transaction:hover {
            background: linear-gradient(120deg, #a03e6e, #c45a89);
        }

        /* Responsividade */
        @media(max-width:768px) {
            nav ul {
                flex-direction: column;
            }

            .welcome {
                flex-direction: column;
                gap: 10px;
            }

            table,
            th,
            td {
                font-size: 14px;
            }
        }

        /* Container centralizado */
        .container {
            max-width: 1000px;
            /* largura máxima agradável */
            margin: 0 auto;
            /* centraliza horizontalmente */
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
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

        .btn-transacao {
            display: inline-block;
            padding: 10px 18px;
            background: linear-gradient(135deg, #c45a89, #a03e6e);
            color: white !important;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-transacao:hover {
            background: linear-gradient(135deg, #a03e6e, #c45a89);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <h1>Sistema Financeiro Pessoal</h1>

        <div class="welcome">
            <p>Bem-vindo, <strong><?php echo htmlspecialchars($usuario_nome); ?></strong></p>
            <a href="logout.php">Sair</a>
        </div>

        <?php exibir_mensagem(); ?>

        <h2>Transações</h2>

        <div>
            <a class="btn-transacao" href="transacoes_formulario.php">Nova Transação</a>
        </div>

        <h3>Filtros</h3>
        <form method="GET" action="transacoes_listar.php">
            <div>
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo">
                    <option value="">Todos</option>
                    <option value="receita" <?php echo $filtro_tipo === 'receita' ? 'selected' : ''; ?>>Receita</option>
                    <option value="despesa" <?php echo $filtro_tipo === 'despesa' ? 'selected' : ''; ?>>Despesa</option>
                </select>
            </div>

            <div>
                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>"
                            <?php echo $filtro_categoria == $categoria['id_categoria'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filtros-buttons">
                <button type="submit">Filtrar</button>
                <a href="transacoes_listar.php">Limpar Filtros</a>
            </div>
        </form>

        <?php if (count($transacoes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes as $transacao): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($transacao['data_transacao'])); ?></td>
                            <td><?php echo htmlspecialchars($transacao['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($transacao['categoria_nome'] ?? 'Sem categoria'); ?></td>
                            <td><?php echo ucfirst($transacao['tipo']); ?></td>
                            <td>R$ <?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
                            <td class="actions">
                                <a href="transacoes_formulario.php?id=<?php echo $transacao['id_transacao']; ?>">Editar</a>
                                <a href="transacoes_excluir.php?id=<?php echo $transacao['id_transacao']; ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir esta transação?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma transação encontrada.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>