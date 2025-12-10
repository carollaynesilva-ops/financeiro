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

//verificar se está editando
$id_categoria =  $_GET['id'] ?? null;
$categoria = null;

if ($id_categoria) {
    // Buscar categoria para editar
    $sql = "SELECT * FROM categoria WHERE id_categoria = :id_categoria AND id_usuario = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_categoria', $id_categoria);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $categoria = $stmt->fetch();

    // Se não encontrou ou não pertence ao usuário, redireciona
    if (!$categoria) {
        set_mensagem('Categoria não encontrada.', 'erro');
        header('Location: categorias_listar.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Sistma Financeiro</title>
    <style>
        /* Fundo geral com rosa clarinho */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffeef7;
            color: #333;
        }

        /* Container central */
        form,
        h1,
        h2,
        nav,
        div {
            max-width: 600px;
            margin: auto;
        }

        /* Título principal */
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #c24f8f;
            font-weight: 700;
        }

        /* Subtítulo */
        h2 {
            text-align: center;
            color: #b43778;
            margin-bottom: 20px;
        }

        /* Área do usuário + logout */
        body>div {
            text-align: center;
            margin-top: 10px;
        }

        .sair {
            text-decoration: none;
            color: white;
            background: #d75791;
            padding: 6px 12px;
            border-radius: 6px;
            margin-left: 10px;
            font-weight: bold;
        }

        .sair:hover {
            background: #c64580;
        }

        /* Navegação */
        nav {
            margin-top: 20px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline-block;
            margin: 0 10px;
        }

        nav a {
            text-decoration: none;
            color: #b43778;
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 6px;
            transition: 0.2s;
        }

        nav a:hover {
            background: #ffd3e8;
        }

        /* Formulário */
        form {
            background: #ffffffc9;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
            box-shadow: 0 0 10px #ffc6e4;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #b43778;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #dd91b8;
            border-radius: 8px;
            background: #fff;
        }

        /* Botões */
        button {
            background: #d75791;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #c64580;
        }

        form a {
            color: #b43778;
            text-decoration: none;
            margin-left: 10px;
        }

        form a:hover {
            text-decoration: underline;
        }

        /* Mensagens */
        .mensagem-sucesso {
            background: #d4ffd9;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
            color: #187d28;
            text-align: center;
        }

        .mensagem-erro {
            background: #ffe1e8;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
            color: #9e2d44;
            text-align: center;
        }
    </style>
</head>

<body>
    dy>
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
    <h2><?php echo $categoria ? 'Editar' : 'Nova'; ?> Categoria</h2>

    <form action="categorias_salvar.php" method="POST">
        <?php if ($categoria): ?>
            <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
        <?php endif; ?>

        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome"
                value="<?php echo $categoria ? htmlspecialchars($categoria['nome']) : ''; ?>"
                required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecione...</option>
                <option value="receita" <?php echo ($categoria && $categoria['tipo'] === 'receita') ? 'selected' : ''; ?>>Receita</option>
                <option value="despesa" <?php echo ($categoria && $categoria['tipo'] === 'despesa') ? 'selected' : ''; ?>>Despesa</option>
            </select>
        </div>

        <div>
            <button type="submit">Salvar</button>
            <a href="categorias_listar.php">Cancelar</a>
        </div>
    </form>
</body>

</html>