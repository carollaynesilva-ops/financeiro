<?php
require_once 'config.php';
require_once 'mensagens.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Financeiro</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: #ffeef7;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            transition: background .3s;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
            opacity: 0.3;
            filter: blur(2px);
            transition: opacity .3s;
        }

        .bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #ffd8ecaa, #ffe9f5aa);
            backdrop-filter: blur(3px);
            transition: background .3s;
        }

        .login-container {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 350px;
            animation: fadeIn .7s ease-out;
        }

        h1 {
            color: #b12e73;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            transition: color .3s;
        }

        .card {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(18px);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            gap: 18px;
            transition: background .3s, box-shadow .3s;
        }

        .campo {
            text-align: left;
        }

        label {
            font-weight: 600;
            color: #912960;
            margin-bottom: 5px;
            display: block;
            transition: color .3s;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #e1a7c7;
            font-size: 1rem;
            background: #fff;
            transition: all .2s;
        }

        input:focus {
            border-color: #c24f8f;
            outline: none;
            box-shadow: 0 0 8px #ffbada;
        }

        .btn {
            margin-top: 10px;
            padding: 12px;
            background: linear-gradient(135deg, #d24b87, #a83b6f);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: transform .2s, box-shadow .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(164, 41, 95, 0.35);
        }

        .registro {
            margin-top: 18px;
            font-size: 0.95rem;
            color: #7a2954;
            transition: color .3s;
        }

        .registro a {
            font-weight: bold;
            text-decoration: none;
            color: #d24b87;
            transition: color .3s;
        }

        .registro a:hover {
            text-decoration: underline;
        }

        .tema-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 20;
            background: #d24b87;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: background .3s;
        }

        .tema-btn:hover {
            background: #b43c72;
        }

        /* DARK MODE */
        body.dark {
            background: #1a0f16;
        }

        body.dark::before {
            opacity: 0.1;
        }

        body.dark .bg-overlay {
            background: linear-gradient(135deg, #3b2037cc, #2a1528cc);
        }

        body.dark h1 {
            color: #ff94d5;
        }

        body.dark .card {
            background: rgba(40, 20, 38, 0.65);
            box-shadow: 0 4px 18px rgba(255, 118, 189, 0.25);
        }

        body.dark label {
            color: #ff8ac8;
        }

        body.dark input {
            background: #2b1b29;
            color: #ffd7ef;
            border-color: #ff9cd5;
        }

        body.dark input:focus {
            box-shadow: 0 0 10px #ff8ac8;
        }

        body.dark .btn {
            background: linear-gradient(135deg, #ff73c9, #c83e8d);
        }

        body.dark .registro {
            color: #ffadda;
        }

        body.dark .registro a {
            color: #ff73c9;
        }

        body.dark .tema-btn {
            background: #ff73c9;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <button class="tema-btn" onclick="mudarTema()">Tema</button>

    <div class="bg-overlay"></div>

    <div class="login-container">
        <h1>Bem-vinda</h1>

        <?php exibir_mensagem(); ?>

        <form action="autenticar.php" method="post" class="card">
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <p class="registro">Não tem conta? <a href="registrar.php">Criar conta</a></p>
    </div>

    <script>
        function mudarTema() {
            document.body.classList.toggle('dark');
        }
    </script>

</body>
</html>
