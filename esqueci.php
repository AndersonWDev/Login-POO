<?php
require('config/conexao.php');

if(isset($_POST['email']) && !empty($_POST['email'])){
    $email = limparPost($_POST['email']);
    $status="confirmado";
    $sql = $pdo->prepare("SELECT * FROM `usuários` WHERE email=? AND status=? LIMIT 1");
    $sql->execute(array($email,$status));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
if($usuario){
        //Enviar email para o usuário fazer nova senha
        $mail = new PHPMailer(true);
        $cod = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $sql = $pdo->prepare("UPDATE `usuários` SET recupera_senha=? WHERE email=?");
        $sql->execute(array($cod,$email));

    try{
                //Recipients
                $mail->setFrom('sistema@emailsistema.com', 'Sistema de Login');//QUEM ESTÁ MANDANDO O EMAIL
                $mail->addAddress($email, $nome); //Pessoa que receberá o email
                
                //Content
                $mail->isHTML(true); //CORPO DO EMAIL COMO HTML 
                $mail->Subject = 'Confirme seu cadastro'; //TITULO DO EMAIL
                $mail->Body    = '<h1>Recuperar a senha</h1><br><br><a class="lkmail" href="https://recuperarsenha.com.br/recuperar.php?cod_confirm='.$cod.'">Recuperar senha</a>';
                
                $mail->send();
                header('location: index.php');
            }catch (Exception $e) {
                echo "Houve um problema ao enviar um email de confirmação: {$mail->ErrorInfo}";
            }}
    
}
else {
            //USUÁRIO EXISTE MAS NÃO CONFIRMADO
            $erro_login = "Houve uma falha ao buscar esse e-mail! tente novamente";}

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
        <p>Informe o e-mail cadastrado no sistema</p>
        <div class="input-group">
            <img class="input-icon" src="Img/social-media.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        <button class="btn-blue" type="submit">Recuperar a Senha</button>
        <a href="index.php">Voltar para login</a>
    </form>
<script src="JS/Script.js"></script>
</body>
</html>