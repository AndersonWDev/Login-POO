<?php
require_once('Crud.php');
require_once('../mailer.php')

class Usuario extends Crud{
    protected string $tabela= 'usuarios';

    function __construct(
        public string $nome,
        private string $email,
        private string $senha,
        private string $repete_senha="",
        private string $recupera_senha="",
        private string $token="",
        private string $codigo_confirmacao="",
        private string $status="",
        public array $erro=[]
        ){}

        public function set_repeticao($repete_senha){
            $this->repete_senha = $repete_senha;
        }
        //MÉTODO DE VALIDAÇÃO
        public function validar_cadastro(){
            //VALIDAÇÃO DO NOME
            if(!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿÇç\s'-]+$/",$this->nome)){
                $this->erro["erro_nome"] = "Somente permitido letras e espaços em branco!";
            }
            //VALIDAÇÃO DO EMAIL
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                $this->erro["erro_email"] = "Formato de email inválido!";
            }
            //VALIDAÇÃO DE SENHA (VER SE TEM PELO MENOS 8 DIGITOS)
            if(strlen($this->senha) < 8){
                $this->erro["erro_senha"] = "A senha precisa de no mínimo 8 caracteres!";
            }
            //VERIFICAR SE A REPETIÇÃO DE SENHA E SENHA SÃO IGUAIS
            if($this->senha !== $this->repete_senha){
            $this->erro["erro_repete"] = "As senhas não estão iguais";
            }
        }
        //MÉTODOS DE INSERIR E MODIFICAR O BANCO
        public function insert(){
        //VERIFICAR SE O EMAIL JÁ ESTÁ CADASTRADO
        $sql = "SELECT  * FROM usuarios WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $usuario = $sql->fetch();
        //SE NÃO EXISTIR - ADD NO BANCO
        if(!$usuario){
            $data_cadastro = date('d/m/Y');
            $senha_cripto = password_hash($this->senha, PASSWORD_DEFAULT);
            $this->status = "pendente";
            $sql = "INSERT INTO $this->tabela VALUES (null,?,?,?,?,?,?,?,?)";
            $sql = DB::prepare($sql);
            $executou = $sql->execute(array(
            $this->nome,
            $this->email,
            $senha_cripto,
            $this->recupera_senha,
            $this->token,
            $this->codigo_confirmacao,
            $this->status,
            $data_cadastro
        ));

        if($executou){
            // Aqui você envia o e-mail de confirmação
            enviarEmailConfirmacao($this->email, $this->codigo_confirmacao);
        }

        return $executou;
        }else{
         $this->erro["erro_geral"] = "Usuário já cadastrado";
        }
        }
        public function uptade($id){
            $sql = "UPDATE $this->tabela SET token=? WHERE id=?";
            $sql = DB::prepare($sql);
            return $sql->execute(array($token,$id));
        }
}