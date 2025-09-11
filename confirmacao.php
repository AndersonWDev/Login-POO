<?php
require('config/conexao.php');
if(isset($_GET['cod_confirm']) && !empty($_GET['cod_conifrm'])){
    //LIMPAR GET
    $cod = limparPost($_GET['cod_confirm']);
    //Consultar se algum usuário tem esse código de confirmação
    //verificar se existe esse usuário
    $sql = $pdo->prepare("SELECT * FROM `usuários` WHERE codigo_confirm=? LIMIT 1");
    $sql->execute(array($cod));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    if($usuario){
        //ATUALIZAR STATUS DO USUÁRIO NO BANCO
        $status = "confirmado";
            $sql = $pdo->prepare("UPDATE `usuários` SET status=? WHERE codigo_confirm=?");
            if($sql->execute(array($status,$cod))){
            header('location: index.php?result=ok');
            exit;
    }else{
        echo "<h1>Código de confirmação inválido!</h1>";
    }
}