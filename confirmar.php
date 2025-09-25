<?php
require_once 'Usuario.php';

if(isset($_GET['codigo'])){
    $codigo = $_GET['codigo'];
    $sql = DB::prepare("SELECT * FROM usuarios WHERE codigo_confirmacao=? LIMIT 1");
    $sql->execute([$codigo]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if($usuario){
        $sql = DB::prepare("UPDATE usuarios SET status='ativo', codigo_confirmacao=NULL WHERE id=?");
        $sql->execute([$usuario['id']]);
        echo "E-mail confirmado! Agora você pode fazer login.";
    }else{
        echo "Código inválido ou expirado.";
    }
}