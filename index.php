<?php
require('config/conexao.php');
//VALIDAÇÕES
if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
    //RECEBER DADOS VINDO DO POST E LIMPAR
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);
    //VERIFICAR SE EXISTE NO BANCO
    $sql = $pdo->prepare("SELECT * FROM `usuários` WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$senha_cript));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    
    //EXISTE O USUÁRIO
    if($usuario){
        //VERIFICAR SE O STATUS FOI CONFIRMADO
        if($usuario['status']=="confirmado"){
            //CRIAR UM TOKEN
            $token = bin2hex(random_bytes(32));//Criptografia segura e aconselhável de token
            //ATUALIZAR TOKEN DO USUÁRIO NO BANCO
            $sql = $pdo->prepare("UPDATE `usuários` SET token=? WHERE email=? AND senha=?");
            if($sql->execute(array($token,$email,$senha_cript))){
                //ARMAZENAR NA SESSÃO
                $_SESSION['TOKEN'] = $token;
                header('location: restrito.php');
                exit;
            }
        } else {
            //USUÁRIO EXISTE MAS NÃO CONFIRMADO
            $erro_login = "Por favor, confirme o cadastro no seu email!";
        }
    } else {
        //USUÁRIO OU SENHA INCORRETOS
        $erro_login = "Usuário ou senha incorretos!";
    }

   
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="CSS/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h2>Login</h2>

        <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){?>        
        <div class="sucesso animate__animated animate__bounce">
        Cadastrado com sucesso
        </div>
        <?php }?>

        <?php if(isset($erro_login)){?>
        <div class="erro-geral animate__animated animate__bounce">
            <?php echo $erro_login;?>
        </div>
        <?php }?>

        <div class="input-group">
            <img class="input-icon" src="Img/social-media.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        <div class="input-group">
            <img class="input-icon" src="Img/lock.png">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        <button class="btn-blue" type="submit">Fazer Login</button>
        <a href="cadastrar.php">Ainda não tenho cadastro</a>
    </form>
<script src="JS/Script.js"></script>
</body>
</html>