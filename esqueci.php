<?php



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="CSS/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>
</head>
<body>
    <form method="post">
        <h2>Esqueci minha senha</h2>
        <div class="input-group">
            <img class="input-icon" src="Img/social-media.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        <div class="input-group">
            <img class="input-icon" src="Img/lock.png">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        <button class="btn-blue" type="submit">Alterar senha</button>
    </form>
</body>
</html>