<?php 
//CONEXÃO COM O BANCO DE DADOS
require('config/conexao.php');
//VERIFICAÇÃO SE EXISTE A POSTAGEM DE ACORDO COM OS CAMPOS
if(isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE OS CAMPOS FORAM PREENCHIDOS
    if(empty($_POST['nome_completo']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){
        $erro_geral = 'Todos os campos são obrigatórios';
    }else{
        //ANTI SQL INJECTION
        $nome = limparPost($_POST['nome_completo']);
        $email = limparPost($_POST['email']) ;
        $senha = limparPost($_POST['senha']);
        //CRIPTOGRAFAR SENHA
        $senha_cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);
        //VERIFICAR NOME
        if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
        $erro_nome = "Somente letras e espaços em branco";
        }
        //VERIFICAR SE O EMAIL É VÁLIDO
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro_email = "Formato inválido de email";
        }
        //VERIFICAR SE A SENHA É VÁLIDA
        if(strlen($senha)<8){
            $erro_senha = "A senha deve conter no mínimo 8 caracteres";
        }
        //VERIFICAR SE A REPETIÇÃO DA SENHA É IGUAL
        if($senha !== $repete_senha){
            $erro_rsenha = "As senhas não estão iguais";
        }
        //VERIFICAR SE CHECKBOX FOI MARCADO
        if($checkbox !=="ok"){
            $errockbox = "Marque a caixa do termos de uso e privacidade";
        }
        //SE TA TUDO OK VAI PARA O BANCO DE DADOS E CADASTRA O USUÁRIO
        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_rsenha) && !isset($errockbox)){
        //VERIFICAR SE O EMAIL JÁ ESTÁ CADASTRADO
        $sql = $pdo->prepare("SELECT * FROM `usuários` WHERE email=? LIMIT 1");
        $sql->execute(array($email));
        $usuario = $sql->fetch();
        //SE NÃO EXISTIR O USUÁRIO -> ADD NO BANCO
        if (!$usuario){
            $recupera_senha="";
            $token = "";
            $codigo_confirm = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);//código aleatorio de 000000 a 999999
            $status = "novo";
            $data_cadastro = date('d/m/Y');
            $sql = $pdo->prepare("INSERT INTO `usuários` VALUES (null,?,?,?,?,?,?,?,?)");
            if($sql->execute(array($nome,$email,$senha_cript,$recupera_senha,$token,$codigo_confirm,$status,$data_cadastro))){
            //CASO SEJA MODO LOCAL
                if($modo == "local"){
                header('location: index.php?result=ok');
            }
            //CASO SEJA MODO DE PRODUÇÃO
            if($modo == "producao"){
            //ENVIAR EMAIL PARA USUÁRIO
            $mail = new PHPMailer(true);
            
            try{
                //Recipients
                $mail->setFrom('sistema@emailsistema.com', 'Sistema de Login');//QUEM ESTÁ MANDANDO O EMAIL
                $mail->addAddress($email, $nome); //Pessoa que receberá o email
                
                //Content
                $mail->isHTML(true); //CORPO DO EMAIL COMO HTML 
                $mail->Subject = 'Confirme seu cadastro'; //TITULO DO EMAIL
                $mail->Body    = '<h1>Por favor connfirme seu email abaixo</h1><br><br><a class="lkmail" href="https://emailconfirm.com.br/confirmacao.php?cod_confirm='.$codigo_confirm.'">Confirmar seu email</a>';
                
                $mail->send();
                header('location: obrigado.php');
            }catch (Exception $e) {
                echo "Houve um problema ao enviar um email de confirmação: {$mail->ErrorInfo}";
            }}

        }else{
            //SE JÀ EXISTIR O USUÁRIO
            $erro_geral = "Usuário já cadastrado";
        }
        }
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
        <h2>Cadastro</h2>
        <?php if(isset($erro_geral)){?>
        <div class="erro-geral animate__animated animate__bounce">
            <?php echo $erro_geral;?>
        </div>
        <?php }?>
        

        <div class="input-group">
            <img class="input-icon" src="Img/card.png">
            <input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';} ?> type="text" placeholder="Nome Completo" name="nome_completo" <?php if(isset($nome)){echo "value='$nome'";} ?> required>
            <?php if(isset($erro_nome)){?>
            <div class="erro"><?php echo $erro_nome; ?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/social-media.png">
            <input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input"';} ?> type="email" placeholder="Digite seu email" name="email" <?php if(isset($email)){echo "value='$email'";} ?> required>
            <?php if(isset($erro_email)){?>
            <div class="erro"><?php echo $erro_email; ?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/lock.png">
            <input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';} ?> type="password" name="senha" placeholder="Senha de no mínimo 8 digitos" <?php if(isset($senha)){echo "value='$senha'";} ?> required>
            <?php if(isset($erro_senha)){?>
            <div class="erro"><?php echo $erro_senha; ?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/unlock.png">
            <input <?php if(isset($erro_geral) or isset($erro_rsenha)){echo 'class="erro-input"';} ?> type="password" name="repete_senha" placeholder="Repita sua senha" required>
            <?php if(isset($erro_rsenha)){?>
            <div class="erro"><?php echo $erro_rsenha; ?></div>
            <?php }?>
        </div>

        <div <?php if(isset($erro_geral) or isset($errockbox)){echo 'class="input-group erro-input"';}else{echo 'class="input-group"';} ?>>
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a href="#" class="link">política de privadade</a> e os <a href="#" class="link">termos de uso</a></label>
            <?php if(isset($errockbox)){?>
            <div class="erro"><?php echo $errockbox; ?></div>
            <?php }?>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>

</body>
</html>