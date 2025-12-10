<?php

require_once 'config.php'; // puxa alguma coisa, tipo um <link>
require_once 'mensagens.php';



if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// echo password_hash('12345678', PASSWORD_DEFAULT);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login- Sistema Financeiro</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffeef7;
            /* fundo rosa clarinho */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Card central */
        form {
            background: #ffffffc9;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 0 12px #ffbcd9;
            width: 320px;
            text-align: center;
        }

        /* Título */
        h1 {
            position: absolute;
            top: 40px;
            width: 100%;
            text-align: center;
            color: #c24f8f;
            font-size: 28px;
            font-weight: 700;
        }

        /* Campos */
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            color: #b43778;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d68ab3;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 15px;
        }

        /* Botão */
        button {
            width: 100%;
            padding: 12px;
            background: #d75791;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #c64580;
        }

        /* Link de cadastro */
        p {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #b43778;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Mensagens */
        .mensagem-sucesso {
            background: #d4ffd9;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            color: #187d28;
            text-align: center;
        }

        .mensagem-erro {
            background: #ffe1e8;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            color: #9e2d44;
            text-align: center;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        /* Ajusta o texto de registro */
        .registro {
            text-align: center;
            font-size: 15px;
            color: #b43778;
            margin: 0;
        }

        .registro a {
            color: #d75791;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Login - Sistema Financeiro</h1>
        <br>
        <br>
        <?php exibir_mensagem(); ?>
        <form action="autenticar.php" method="post">
            <br>
            <br>
            <div>
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>
            <br>
            <div>
                <label for="email">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <br>
            <div>
                <button type="submit">
                    Entrar
                </button>
            </div>

        </form>

        <p class="registro">Não tem conta? <a href="registrar.php">Cadastrar-se aqui.</a></p>

    </div>

</body>

</html>