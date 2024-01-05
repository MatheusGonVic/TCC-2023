<?php
require_once "config.php";
require_once "conexao.php";
require_once "tarefa.model.php";
require_once "tarefa.service.php";

// Inicializa as variáveis
$new_password_err = $confirm_password_err = '';
$new_password = $confirm_password = '';

// Verificar se a chave 'token' está definida na matriz $_GET
if (isset($_GET['token'])) {
    $token = $_GET["token"];

    // Verificar se o token foi fornecido
    if (!empty($token)) {
        $conexao = new Conexao();
        $tarefa = new Tarefa();
        $tarefaService = new TarefaService($conexao, $tarefa);

        // Verificar se o token é válido
        if ($tarefaService->verificarToken($token)) {

            // Verificar se o formulário foi enviado
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = $_POST["new_password"];
                $confirm_password = $_POST["confirm_password"];

                // Validar as senhas
                if ($new_password != $confirm_password) {
                    $new_password_err = "As senhas não coincidem.";
                } else {
                    // Atualizar a senha no banco de dados
                    $tarefaService->atualizarSenha($token, $new_password);

                    // Mensagem de sucesso
                    $success_message = "Senha atualizada com sucesso!";
                }
            }
        } else {
            $error_message = "Token inválido.";
        }
    } 
} else {
    $error_message = "Token não encontrado.";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <div class="wrapper" style="margin-top: -40px;">
            <h2>Redefinir senha</h2>
            <p>Por favor, preencha este formulário para redefinir sua senha.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?token=' . $token); ?>" method="post">
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
                    <a class="btn custom-btn ml-2" href="login.php" style="color: white;">Cancelar</a>
                   <a class="btn btn-primary ml-2" href="login.php" style="background-color: #1B2E35; color: white;">Login</a>
                </div>
            </form>
            <?php
            // Exibir mensagens de erro ou sucesso
            if (isset($error_message)) {
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<p style="font-size: 18px; color: red;">' . $error_message . '</p>';
                echo '<button onclick="voltarAoInicio()" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Voltar ao Início</button>';
                echo '</div>';
            } elseif (isset($success_message)) {
                // Exibir mensagem de sucesso com o SweetAlert2
                echo '<script>';
                echo 'Swal.fire({';
                echo 'title: "Sucesso!",';
                echo 'text: "' . $success_message . '",';
                echo 'icon: "success",';
                echo 'showConfirmButton: false,';
                echo 'timer: 2000';
                echo '});';
                echo '</script>';
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <script src="apresenta_senha.js"></script>
</body>
</html>
