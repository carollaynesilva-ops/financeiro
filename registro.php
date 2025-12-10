<?php
require_once 'config.php';
require_once 'mensagens.php';

// Se já estiver logado, redireciona para o index
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Financeiro</title>
    <style>
        /* Fundo geral com rosa clarinho */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffeef7; /* Rosa clarinho */
            color: #333;
            display: flex; /* Para centralizar o conteúdo verticalmente */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Ocupa a altura total da viewport */
            box-sizing: border-box; /* Inclui padding e border no tamanho total */
        }

        /* Container principal para o conteúdo */
        .container {
            max-width: 450px; /* Um pouco mais estreito para formulários de login/registro */
            width: 90%; /* Responsivo */
            margin: 20px auto; /* Margem superior/inferior para respirar */
        }

        /* Título principal */
        h1 {
            text-align: center;
            color: #c24f8f; /* Rosa escuro */
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Subtítulo/Título do Formulário */
        h2 {
            text-align: center;
            color: #b43778; /* Rosa médio */
            margin-top: 0;
            margin-bottom: 25px;
        }

        /* Formulário (Card) */
        form {
            background: #ffffffc9;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #ffc6e4; /* Sombra rosa clara */
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dd91b8;
            border-radius: 8px;
            background: #fff;
            box-sizing: border-box; /* Garante que o padding não aumente a largura total */
        }
        
        input:focus {
            outline: none;
            border-color: #d75791; /* Borda focada mais escura */
            box-shadow: 0 0 5px rgba(215, 87, 145, 0.5);
        }


        /* Botão de Cadastro */
        button[type="submit"] {
            background: #d75791; /* Rosa vibrante */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            width: 100%; /* Ocupa a largura total */
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #c64580; /* Rosa mais escuro no hover */
        }

        /* Link de login */
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1em;
            color: #333;
        }
        
        .login-link a {
            color: #b43778;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .login-link a:hover {
            text-decoration: underline;
            color: #d75791;
        }

        /* Estilos das Mensagens (iguais ao código de exemplo) */
        .mensagem-sucesso, .mensagem-erro {
            max-width: 450px;
            margin: 15px auto;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensagem-sucesso {
            background: #d4ffd9;
            color: #187d28;
        }

        .mensagem-erro {
            background: #ffe1e8;
            color: #9e2d44;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema Financeiro Pessoal</h1>
        <h2>Cadastro de Usuário</h2>
        
        <?php exibir_mensagem(); ?>
        
        <form action="registrar.php" method="POST">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required minlength="6">
            </div>
            
            <div>
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>
            
            <div>
                <button type="submit">Cadastrar</button>
            </div>
        </form>
        
        <p class="login-link">Já tem conta? <a href="login.php">Faça login aqui</a></p>
    </div>
</body>
</html>