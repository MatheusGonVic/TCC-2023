<?php
    // Inicialize a sessão
    session_start();
     
    // Verifique se o usuário está logado, caso contrário, redirecione para a página de login
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
     
    // Incluir arquivo de configuração
    require_once "config.php";
     
    // Defina variáveis e inicialize com valores vazios
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
     
    // Processando dados do formulário quando o formulário é enviado
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        // Validar nova senha
        if(empty(trim($_POST["new_password"]))){
            $new_password_err = "Por favor insira a nova senha.";     
        } elseif(strlen(trim($_POST["new_password"])) < 6){
            $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
        } else{
            $new_password = trim($_POST["new_password"]);
        }
        
        // Validar e confirmar a senha
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Por favor, confirme a senha.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "A senha não confere.";
            }
        }
            
        // Verifique os erros de entrada antes de atualizar o banco de dados
        if(empty($new_password_err) && empty($confirm_password_err)){
            // Prepare uma declaração de atualização
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            
            if($stmt = $pdo->prepare($sql)){
                // Vincule as variáveis à instrução preparada como parâmetros
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
                
                // Definir parâmetros
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                // Tente executar a declaração preparada
                if($stmt->execute()){
                    // Senha atualizada com sucesso. Destrua a sessão e redirecione para a página de login
                    session_destroy();
                    header("location: login.php");
                    exit();
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
    <title>Redefinir senha</title>
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
        <div class="wrapper"  style="margin-top: -40px;">
            <h2>Redefinir senha</h2>
            <p>Por favor, preencha este formulário para redefinir sua senha.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label>Nova senha</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePassword('password', 'eye-icon')">
                                <i id="eye-icon" class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirme a senha</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm-password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePassword('confirm-password', 'eye-icon-confirm')">
                                <i id="eye-icon-confirm" class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn custom-btn" value="Redefinir">
                    <a class="btn custom-btn ml-2" href="welcome.php" style="color: white;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="apresenta_senha.js"></script>
</body>
</html>
