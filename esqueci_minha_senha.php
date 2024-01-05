<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-light bg-light" style="margin-bottom: -58px;">
        <div class="container">
        <a class="navbar-brand d-flex align-items-center ml-auto" style="margin-right: 500px;" href="login.php">
            <img src="img/logo2.jpeg" width="40" height="40" class="d-inline-block align-top" alt="">
            <h5 class="ml-2 mb-0">Lista Tarefas</h5>
        </a>
        </div>
    </nav> 
        <div style="margin-top: 20px";></div>
        <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
            <div class="wrapper" style="margin-top: -150px; max-width: 400px;">
                <h2>Esqueci minha senha</h2>
                <p>Por favor, digite o seu endereço de e-mail abaixo para redefinir a sua senha.</p>
                <form action="enviar_email_senha.php" method="post">
                    <div class="form-group">
                        <label>Endereço de e-mail:</label>
                        <input type="email" name="username" class="form-control" required>
                    </div>    
                    <div class="form-group" style="display: flex; align-items: center;">
                        <input type="submit" class="btn btn-primary" value="Redefinir senha" style="background-color: #1B2E35;">
                        <a href="welcome.php" class="btn ml-2 text-white" style="background-color: #59E4A8; margin-left: 10px;">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </body>   
</html>

