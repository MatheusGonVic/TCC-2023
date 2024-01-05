<?php
    // Inicialize a sessão
    session_start();
     
    // Verifique se o usuário já está logado, em caso afirmativo, redirecione-o para a página de boas-vindas
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: welcome.php");
        exit;
    }
     
    // Incluir arquivo de configuração
    require_once "config.php";
     
    // Defina variáveis e inicialize com valores vazios
    $username = $password = "";
    $username_err = $password_err = $login_err = "";
     
    // Processando dados do formulário quando o formulário é enviado
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        // Verifique se o nome de usuário está vazio
        if(empty(trim($_POST["username"]))){
            $username_err = "Por favor, insira o nome de usuário.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        // Verifique se a senha está vazia
        if(empty(trim($_POST["password"]))){
            $password_err = "Por favor, insira sua senha.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validar credenciais
        if(empty($username_err) && empty($password_err)){
            // Prepare uma declaração selecionada
            $sql = "SELECT id, username, password FROM users WHERE username = :username";
            
            if($stmt = $pdo->prepare($sql)){
                // Vincule as variáveis à instrução preparada como parâmetros
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                
                // Definir parâmetros
                $param_username = trim($_POST["username"]);
                
                // Tente executar a declaração preparada
                if($stmt->execute()){
                    // Verifique se o nome de usuário existe, se sim, verifique a senha
                    if($stmt->rowCount() == 1){
                        if($row = $stmt->fetch()){
                            $id = $row["id"];
                            $username = $row["username"];
                            $hashed_password = $row["password"];
                            if(password_verify($password, $hashed_password)){
                                // A senha está correta, então inicie uma nova sessão
                                session_start();
                                
                                // Armazene dados em variáveis de sessão
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;                            
                                
                                // Redirecionar o usuário para a página de boas-vindas
                                header("location: welcome.php");
                            } else{
                                // A senha não é válida, exibe uma mensagem de erro genérica
                                $login_err = "Nome de usuário ou senha inválidos.";
                            }
                        }
                    } else{
                        // O nome de usuário não existe, exibe uma mensagem de erro genérica
                        $login_err = "Nome de usuário ou senha inválidos.";
                    }
                } else{
                    echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
                }

                // Fechar declaração
                unset($stmt);
            }
        }
        
        // Fechar conexão
        unset($pdo);
    }
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="wrapper" style="display: flex; justify-content: center; align-items: center; margin: 10px 0;">
            <!-- Image on the left with margin-right -->
            <img src="img/login.jpg" style="width: 35%; height: auto; margin-right: 20px;">
            
            <!-- Login form on the right with margin-left -->
            <div style="width: 30%; margin-left: 20px;">
                <h2>Login</h2>
                <p>Por favor, preencha os campos para fazer o login.</p>
            
                <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
                ?>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <label>Senha</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" autocomplete="current-password">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword()">
                                    <i id="eye-icon" class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center;">
                        <input type="submit" class="btn btn-primary" value="Entrar" style="background-color: #1B2E35;">
                        <a href="esqueci_minha_senha.php" style="color: #59E4A8; margin-left: 10px;">Esqueci minha senha</a>
                    </div>
                    <p>Não tem uma conta? <a href="register.php" style="color: #59E4A8;">Inscreva-se agora</a>.</p>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>







