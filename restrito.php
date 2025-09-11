<?php
require('config/conexao.php');

//VERIFICAÇÃO DE AUTENTICAÇÃO
$user = auth($_SESSION['TOKEN']);
if($user){
    echo "<h1> SEJA BEM-VINDO <b>".$user['nome']."!</b></h1>";
    echo "<a href='logout.php'>Sair da conta</a>";
}else{
    //CASO NÃO ESTEJA LOGADO REDIRECIONAR PARA LOGIN
    header('location: index.php');
}
?>