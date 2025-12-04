<?php

require_once 'config.php';
require_once'mensagens.php'; // Esta linha inclui (importa) o conteúdo do arquivo chamado config.php.
// Este arquivo geralmente contém informações essenciais de configuração, como credenciais de banco de dados (host, usuário, senha, nome do DB), chaves de API, ou constantes do sistema.
// require_once garante que o arquivo seja incluído apenas uma vez, e se ele não for encontrado, o script parará (erro fatal).

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    // echo "Email: $email - Senha:$senha";


    //validar os campos
    if (empty($email) || empty($senha)) {
        set_mensagem('Preencha todos os campos', 'erro');
        header('Location:login.php');
        exit;
    }

    //Buscar usuario no banco de dados
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch();

    //verificar se o usuario existe e se a senha esta correta

    if($usuario && password_verify($senha, $usuario['senha'])){
        //Login bem-sucedido
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];

        header('Location: index.php');
        exit;

    }else{
        set_mensagem('E-mail ou senha incorretos','erro');
        header('Location:login.php');
        exit;

    }

} else {

    header('Location: login.php');
    exit;
}
