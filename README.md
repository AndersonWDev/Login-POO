Este projeto é um sistema de autenticação em PHP orientado a objetos.  
Ele permite o cadastro e login de usuários com segurança moderna, utilizando `password_hash()`(bcrypt) para armazenamento de senhas.  
Inclui suporte a sessões com token e validação de dados.

---

## ⚙️ Tecnologias utilizadas
- PHP 8+  
- MySQL  
- PDO para conexão com o banco  
- Programação Orientada a Objetos (POO)  

---

## 📂 Estrutura do projeto
- `DB.php` → conexão com banco de dados  
- `Crud.php` → classe abstrata para operações básicas (insert, update, delete)  
- `Usuario.php` → cadastro, validação de usuários e criptografia de senha  
- `Login.php` → autenticação, gerenciamento de sessão e token  
- `config.php` → configuração do ambiente e credenciais do banco  
- Pastas extras para assets, uploads e páginas restritas  

---

## 🔑 Funcionalidades
- Cadastro de usuários com validação de nome, email e senha  
- Login seguro utilizando `password_hash()` e `password_verify()`  
- Migração automática de usuários antigos com SHA-1  
- Sessões seguras utilizando tokens únicos  
- Estrutura pronta para recuperação de senha e confirmação de email  

---

## 🛠️ Como rodar o projeto
1. Clone o repositório:  
   ```bash
   git clone https://github.com/AndersonWDev/Login-POO.git
