<?php
require_once('../class/config.php');
require_once('../autoload.php');

$login = new Login();
// if(!isset($_SESSION['TOKEN'])){
//     header('location: ../index.php');
//     exit;
// }

$login->isAuth($_SESSION['TOKEN']);
echo "<h1>Bem-vindo $login->nome!</h1>";