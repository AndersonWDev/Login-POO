<?php
require_once('class/config.php');
require_once('autoload.php');
//Validação de campos
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //Receber e limpar valores do post
    $nome = limpaPost($_POST['nome']);
    $email = limpaPost($_POST['email']);
    $senha = limpaPost($_POST['senha']);
    $repete_senha = limpaPost($_POST['repete_senha']);
    //VERIFICAR SE OS CAMPOS ESTÃOS VAZIOS
    if(empty($nome) or empty($email) or empty($senha) or empty($repete_senha) or empty($_POST['termos'])){
        $erro_geral = "Todos os campos são obrigatórios!";
    }else{
        //INSTANCIAR A CLASSE USUARIO
        $usuario = new Usuario($nome,$email,$senha);
        //SETAR A REPETIÇÃO DE SENHA
        $usuario->set_repeticao($repete_senha);//SETAR A REPETIÇÃO DE SENHA
        //VALIDAR O CADASTRO
        $usuario->validar_cadastro();
        //SE NÃO TIVER NENHUM ERRO , INSERIR NO BANCO
        if(empty($usuario->erro)){
        if($usuario->insert()){
            header('location: index.php?sucesso=ok');
            exit;
        }
        }//else{
            //CASO DE ERRADO
            //$erro_geral = $usuario->erro["erro_geral"];
     //}
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
    <title>Cadastro</title>
</head>
<body>
    <form method="post">
        <h2>Cadastro</h2>
        <?php if(isset($usuario->erro["erro_geral"])){?>
        <div class="erro-geral animate__animated animate__bounce">
            <?php echo $usuario->erro["erro_geral"]; ?>
        </div>
     <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="Img/card.png">
            <input <?php if(isset($usuario->erro["erro_nome"]) or isset($erro_geral)){echo 'class="erro-input"';} ?> type="text" placeholder="Nome Completo" name="nome" <?php if(isset($_POST['nome'])){echo 'value="'.$_POST['nome'].'"';} ?> required>
  
            <div class="erro"><?php if(isset($usuario->erro["erro_nome"])){ echo $usuario->erro["erro_nome"];}?></div>
            
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/social-media.png">
            <input <?php if(isset($usuario->erro["erro_email"]) or isset($erro_geral)){echo 'class="erro-input"';} ?> type="email" placeholder="Digite seu email" name="email" <?php if(isset($_POST['email'])){echo 'value="'.$_POST['email'].'"';} ?> required>
         
            <div class="erro"><?php if(isset($usuario->erro["erro_email"])){ echo $usuario->erro["erro_email"];}?></div>
          
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/lock.png">
            <input <?php if(isset($usuario->erro["erro_senha"]) or isset($erro_geral)){echo 'class="erro-input"';} ?> type="password" name="senha" placeholder="Senha de no mínimo 8 digitos" <?php if(isset($_POST['senha'])){echo 'value="'.$_POST['senha'].'"';} ?> required>
      
            <div class="erro"><?php if(isset($usuario->erro["erro_senha"])){ echo $usuario->erro["erro_senha"];}?></div>
         
        </div>

        <div class="input-group">
            <img class="input-icon" src="Img/unlock.png">
            <input <?php if(isset($usuario->erro["erro_repete"]) or isset($erro_geral)){echo 'class="erro-input"';} ?> type="password" name="repete_senha" placeholder="Repita sua senha" required>
          
            <div class="erro"><?php if(isset($usuario->erro["erro_repete"])){ echo $usuario->erro["erro_repete"];}?></div>
        
        </div>

        <div <?php if(isset($erro_geral) && $erro_geral=="Todos os campos são obrigatórios!"){echo 'class="erro-input input-group"';}else{ echo 'class="input-group"'; }?>>
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a href="#" class="link">política de privadade</a> e os <a href="#" class="link">termos de uso</a></label>
           
            <div class="erro"></div>
           
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>

</body>
</html>