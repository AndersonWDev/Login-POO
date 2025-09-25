<?php
require_once('DB.php');

class Login{
    protected string $tabela = 'usuarios';
    public string $email;
    private string $senha;
    public string $nome;
    private string $token;
    public array $erro=[];

    public function auth($email, $senha) {
    // Buscar usuário só pelo email
    $sql = "SELECT * FROM $this->tabela WHERE email=? LIMIT 1";
    $sql = DB::prepare($sql);
    $sql->execute([$email]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Se precisar rehash (ex: mudou custo do bcrypt), atualiza automaticamente
        if (password_needs_rehash($usuario['senha'], PASSWORD_DEFAULT)) {
            $novoHash = password_hash($senha, PASSWORD_DEFAULT);
            $sqlUpdate = DB::prepare("UPDATE $this->tabela SET senha=? WHERE id=?");
            $sqlUpdate->execute([$novoHash, $usuario['id']]);
        }

        // Criar token
        $this->token = bin2hex(random_bytes(32));

        // Atualizar token no banco (só pelo id/email)
        $sql = "UPDATE $this->tabela SET token=? WHERE id=? LIMIT 1";
        $sql = DB::prepare($sql);
        if ($sql->execute([$this->token, $usuario['id']])) {
            $_SESSION['TOKEN'] = $this->token;
            header('location: restrita/index.php');
            exit;
        } else {
            $this->erro['erro_geral'] = "Falha ao se comunicar com o servidor";
        }
    } else {
        $this->erro["erro_geral"] = "Usuário ou senha incorretos!";
    }
}
    public function isAuth($token){
        $sql = "SELECT * FROM $this->tabela WHERE token=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($token));
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);
        if($usuario){
            $this->nome = $usuario["nome"];
            $this->email = $usuario["email"];
        }else{
            header('location: ../index.php');
            exit;
        }
    }
}