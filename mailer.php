<?php
// Incluir arquivos do PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail(string $destinatario, string $assunto, string $mensagem): bool {
    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';           // Servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'seuemail@gmail.com';      // Seu e-mail
        $mail->Password   = 'sua_senha_ou_appPassword'; // Senha ou App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('seuemail@gmail.com', 'Suporte');
        $mail->addAddress($destinatario);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $mensagem;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        return false;
    }
}
function enviarEmailConfirmacao(string $email, string $codigo_confirmacao): bool {
    $codigo_confirmacao = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $link = "http://seusite.com/confirmar.php?codigo=$codigo_confirmacao";
    $assunto = "Confirme seu e-mail";
    $mensagem = "
        <h2>Confirmação de Cadastro</h2>
        <p>Olá! Para confirmar seu e-mail, clique no link abaixo:</p>
        <a href='$link'>$link</a>
        <p>Se você não se cadastrou, ignore este e-mail.</p>
    ";
    return enviarEmail($email, $assunto, $mensagem);
}

function enviarEmailResetSenha(string $email, string $token): bool {
    $link = "http://seusite.com/resetar.php?token=$token";
    $assunto = "Redefinição de senha";
    $mensagem = "
        <h2>Redefinir Senha</h2>
        <p>Você solicitou a redefinição da senha. Clique no link abaixo para alterar sua senha:</p>
        <a href='$link'>$link</a>
        <p>Se você não solicitou, ignore este e-mail.</p>
    ";
    return enviarEmail($email, $assunto, $mensagem);
}