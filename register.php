<?php
    // Incluir arquivo de configuração
    require_once "config.php";
     
    // Defina variáveis e inicialize com valores vazios
    $username = $name = $password = $confirm_password = "";
    $username_err = $name_err = $password_err = $confirm_password_err = "";
     
    // Processando dados do formulário quando o formulário é enviado
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $name = isset($_POST["name"]) ? $_POST["name"] : '';

        // Validar e-mail do usuário
        if(empty(trim($_POST["username"]))){
            $username_err = "Por favor coloque o e-mail do usuário.";
        } else{
            // Prepare uma declaração selecionada
            $sql = "SELECT id FROM users WHERE username = :username";
            
            if($stmt = $pdo->prepare($sql)){
                // Vincule as variáveis à instrução preparada como parâmetros
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                
                // Definir parâmetros
                $param_username = trim($_POST["username"]);
                
                // Tente executar a declaração preparada
                if($stmt->execute()){
                    if($stmt->rowCount() == 1){
                        $username_err = "Este nome de usuário já está em uso.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
                }

                // Fechar declaração
                unset($stmt);
            }
        }

        if (empty(trim($_POST["name"]))) {
            $name_err = "Por favor, coloque seu nome.";
        } else {
            $name = trim($_POST["name"]);
        }
        
        // Validar senha
        if(empty(trim($_POST["password"]))){
            $password_err = "Por favor insira uma senha.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "A senha deve ter pelo menos 6 caracteres.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validar e confirmar a senha
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Por favor, confirme a senha.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "A senha não confere.";
            }
        }
        
        // Verifique os erros de entrada antes de inserir no banco de dados
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($name_err)){
            // Prepare uma declaração de inserção
            $sql = "INSERT INTO users (username, password, name) VALUES (:username, :password, :name)";

            if($stmt = $pdo->prepare($sql)){
                // Vincule as variáveis à instrução preparada como parâmetros
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);

                // Definir parâmetros
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Cria um hash de senha
                $param_name = $name; // Adicione o nome

                // Tente executar a declaração preparada
                if($stmt->execute()){
                    // Redirecionar para a página de login
                    header("location: confirmacao_cadastro.php");
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
    <title>Cadastro</title>
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
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="wrapper"  style="margin-top: 55px;">
            <h2>Cadastro</h2>
            <p>Por favor, preencha este formulário para criar uma conta.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>E-mail:</label>
                    <input type="email" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                </div>  
                <div class="form-group">
                    <label>Senha:</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirme a senha:</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
               <div class="form-group">
                    <input type="submit" class="btn" value="Criar Conta" style="background-color: #59E4A8;">
                    <input type="reset" class="btn ml-2 text-white" value="Apagar Dados" style="background-color: #1B2E35;">

                </div>
                <p>Já tem uma conta? <a href="login.php" style="color: #59E4A8;">Entre aqui.</a></p>
            </form>
        </div>    
    </div>    
</body>
</html>