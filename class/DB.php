<?php
require_once('config.php');
//Classe que faz conexão com o banco de dados
class DB{
    private static $pdo;
    public static function instanciar(){
        if(!isset(self::$pdo)){
            try{
                //CONEXÃO COM O BANCO DE DADOS
                self::$pdo = new PDO('mysql:host='.SERVIDOR.';dbname='.BANCO,USUARIO,SENHA);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }catch(PDOException $erro){
                echo "Falha ao se conectar com o banco: ".$erro->getMessage();
            }
        }
        return self::$pdo;
    }
    public static function prepare($sql){
        return self::instanciar()->prepare($sql);
    }
}