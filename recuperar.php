<?php 
//CONEXÃO COM O BANCO DE DADOS
require('config/conexao.php');

if(isset($_GET['cod_confirm']) && !empty($_GET['cod_confirm'])){
 $cod = limparPost($_GET['cod_confirm']);
 
    if(isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE OS CAMPOS FORAM PREENCHIDOS
    if(empty($_POST['senha']) or empty($_POST['repete_senha'])){
        $erro_geral = 'Todos os campos são obrigatórios';
    }else{
        //ANTI SQL INJECTION
        $senha = limparPost($_POST['senha']);
        //CRIPTOGRAFAR SENHA
        $senha_cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        
       
        //VERIFICAR SE A SENHA É VÁLIDA
        if(strlen($senha)<8){
            $erro_senha = "A senha deve conter no mínimo 8 caracteres";
        }
        //VERIFICAR SE A REPETIÇÃO DA SENHA É IGUAL
        if($senha !== $repete_senha){
            $erro_rsenha = "As senhas não estão iguais";
        }
       
        //SE TA TUDO OK VAI PARA O BANCO DE DADOS E CADASTRA O USUÁRIO
        if(!isset($erro_geral)!isset($erro_senha) && !isset($erro_rsenha)){
        $sql = $pdo->prepare("SELECT * FROM `usuários` WHERE recupera_senha=?");
        $sql->execute(array($cod));
        $usuario = $sql->fetch();
        if(!$usuario){
            echo "Recuperação de senha inválida !";
        }else{
            $sql = $pdo->prepare("UPDATE `usuários` SET senha=? WHERE recupera_senha=?");
            if($sql->execute(array($senha_cript,$cod))){
              
                header('location: index.php');
            
    }}}





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
        <h2>Cadastro</h2>
        <?php if(isset($erro_geral)){?>
        <div class="erro-geral animate__animated animate__bounce">
            <?php echo $erro_geral;?>
        </div>
        <?php }?>
        

        <div class="input-group">
            <img class="input-icon" src="Img/lock.png">
            <input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';} ?> type="password" name="senha" placeholder="Digite a nova senha"<?php if(isset($senha)){echo "value='$senha'";} ?> required>
            <?php if(isset($erro_senha)){?>
            <div class="erro"><?php echo $erro_senha; ?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/unlock.png">
            <input <?php if(isset($erro_geral) or isset($erro_rsenha)){echo 'class="erro-input"';} ?> type="password" name="repete_senha" placeholder="Repita sua nova senha" required>
            <?php if(isset($erro_rsenha)){?>
            <div class="erro"><?php echo $erro_rsenha; ?></div>
            <?php }?>
            </div>

        <button class="btn-blue" type="submit">Mudar a senha</button>
    </form>

</body>
</html>