<?php
session_start();

//PHPMailer - Requerimento
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Local (Modo em testes , produção é o site na internet para valer)
$modo = "local";
if($modo == 'local'){
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "login";
}
try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


}catch(PDOexception $erro){
    echo "Falha ao se conectar com o banco";
}

if($modo == 'producao'){
    /*
    $servidor = "localhost";
    $usuario = "usuario do host que tu comprou";
    $senha = "senha do host que tu comprou";
    $banco = "login do host que tu comprou";*/
}

function limparPost($dados){
$dados = trim($dados);
$dados = stripslashes($dados);
$dados = htmlspecialchars($dados);
return $dados;
}

function auth($tokenSession){
    global $pdo;
    //VERIFICAR SE TEM AUTORIZAÇÃO
$sql = $pdo->prepare("SELECT * FROM `usuários` WHERE token=? LIMIT 1");
$sql->execute(array($tokenSession));
$usuario = $sql->fetch(PDO::FETCH_ASSOC);
//SE NÃO EXISTE USUÁRIO
if(!$usuario){
    return false;
}else{
   return $usuario;
}
}
