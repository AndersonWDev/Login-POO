<?php
session_start();
//CONFIGURAÇÕES DO BANCO DE DADOS
//ATRAVÉS DE CONSTANTES
define('SERVIDOR','localhost');
define('USUARIO','root');
define('SENHA','');
define('BANCO','login');

function limpaPost($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}