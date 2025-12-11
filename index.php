<?php
require_once 'config.php';
require_once 'mensagens.php';

// <!-- verificar se o usuario está logado -->
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

$sql_receitas = "SELECT SUM(valor) as total FROM transacao 
                 WHERE id_usuario = :usuario_id AND tipo = 'receita'";
$stmt_receitas = $conn->prepare($sql_receitas);
$stmt_receitas->bindParam(':usuario_id', $usuario_id);
$stmt_receitas->execute();
$total_receitas = $stmt_receitas->fetch()['total'] ?? 0;

$sql_despesas = "SELECT SUM(valor) as total FROM transacao 
                 WHERE id_usuario = :usuario_id AND tipo = 'despesa'";
$stmt_despesas = $conn->prepare($sql_despesas);
$stmt_despesas->bindParam(':usuario_id', $usuario_id);
$stmt_despesas->execute();
$total_despesas = $stmt_despesas->fetch()['total'] ?? 0;

$saldo = $total_receitas - $total_despesas;

// Buscar últimas transações
$sql_ultimas = "SELECT t.*, c.nome as categoria_nome 
                FROM transacao t 
                LEFT JOIN categoria c ON t.id_categoria = c.id_categoria 
                WHERE t.id_usuario = :usuario_id 
                ORDER BY t.data_transacao DESC, t.id_transacao DESC 
                LIMIT 5";
$stmt_ultimas = $conn->prepare($sql_ultimas);
$stmt_ultimas->bindParam(':usuario_id', $usuario_id);
$stmt_ultimas->execute();
$ultimas_transacoes = $stmt_ultimas->fetchAll();


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Financeiro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="tema-dropdown">
            <button class="tema-toggle">Temas ▼</button>
            <div class="tema-opcoes">
                <button onclick="mudarTema('tema-padrao')">Padrão</button>
                <button onclick="mudarTema('tema-dark')">Dark Rosa</button>
                <button onclick="mudarTema('tema-cupcake')">Cupcake</button>
            </div>
        </div>
        <h1>Sistema Financeiro</h1>

        <div>
            <p>Bem-vindo. <strong> <?php echo $usuario_nome ?> </strong></p>
            <a href="logout.php" class="sair">Sair</a>
        </div>
        <?php exibir_mensagem(); ?>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="categorias_listar.php">Categorias</a></li>
                <li><a href="transacoes_listar.php">Transações</a></li>
            </ul>
        </nav>
        <h2>Resumo Financeiro</h2>
        <div class="resumo">
            <div class="card receita">
                <h3>Receitas</h3>
                <p> R$ <?php echo number_format($total_receitas, 2, ',', '.') ?></p>
            </div>

            <div class="card despesa">
                <h3>Despesas</h3>
                <p> R$ <?php echo number_format($total_despesas, 2, ',', '.') ?></p>
            </div>

            <div class="card saldo">
                <h3>Saldo</h3>
                <p> R$ <?php echo number_format($saldo, 2, ',', '.') ?></p>
            </div>
        </div>

        <h2>Últimas Transações</h2>

        <?php if (count($ultimas_transacoes) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimas_transacoes as $transacao): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($transacao['data_transacao'])); ?></td>
                            <td><?php echo htmlspecialchars($transacao['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($transacao['categoria_nome'] ?? 'Sem categoria'); ?></td>
                            <td><?php echo ucfirst($transacao['tipo']); ?></td>
                            <td>R$ <?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <br>
            <p><a href="transacoes_listar.php" class="ver-transacoes-btn">Ver todas as transações</a></p>

        <?php else: ?>
            <p>Nenhuma transação cadastrada ainda.</p>
            <p><a href="transacoes_formulario.php">Cadastrar primeira transação</a></p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mudarTema(tema) {
            document.body.className = tema;
        }
    </script>
    <script>
        function mudarTema(tema) {
            document.body.className = tema;
        }

        // Dropdown: abre e fecha
        document.querySelector('.tema-toggle').addEventListener('click', function() {
            document.querySelector('.tema-opcoes').style.display =
                document.querySelector('.tema-opcoes').style.display === 'flex' ?
                'none' :
                'flex';
        });
    </script>


</body>

</html>