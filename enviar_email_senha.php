<?php
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "config.php";
    require_once "conexao.php";
    require_once "tarefa.model.php";
    require_once "tarefa.service.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\OAuth;
    use League\OAuth2\Client\Provider\Google;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];

        // Verify if the email is valid
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $conexao = new Conexao();
            $tarefa = new Tarefa();
            $tarefaService = new TarefaService($conexao, $tarefa);

            // Verify if the email is registered in the system
            if ($tarefaService->existeUsuario($username)) {
                // Generate a unique token for the user
                $token = bin2hex(random_bytes(32));

                // Store the token in the database associated with the corresponding user
                $tarefaService->esqueciSenha($token, $username);

                // Send the password reset email to the user
                $reset_link = "http://listatarefastccmatheus.epizy.com/reset-senha.php?token=" . $token;

                $provider = new Google([
                    'clientId' => '786136303340-07r416kbkebckgmgth7dpi6futc56g63.apps.googleusercontent.com',
                    'clientSecret' => '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',
                ]);

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->setOAuth(
                    new OAuth([
                        'provider' => $provider,
                        'clientId' => '786136303340-07r416kbkebckgmgth7dpi6futc56g63.apps.googleusercontent.com',
                        'clientSecret' => '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',
                        'refreshToken' => '4/0AbUR2VPYkWNUyAKTN36XATinXDS-AUS-Rca00OA7UDBq2tWxBBXWJj9x3OFbKNO5j4LfhQ',
                    ])
                );

                $mail->setFrom('listatarefastccmatheus@gmail.com', 'Matheus');
                $mail->addAddress($username);
                $mail->Subject = 'Redefinir senha';
                $mail->Body = "Olá,\n\nPara redefinir sua senha, clique no link abaixo:\n\n" . $reset_link . "\n\nSe você não solicitou a redefinição da senha, por favor ignore este e-mail.\n\nAtenciosamente,\nSua equipe de suporte.";

                try {
                    $mail->send();
                    echo "Um e-mail com as instruções para redefinir sua senha foi enviado para o endereço fornecido.";
                } catch (Exception $e) {
                    echo "Ocorreu um erro ao enviar o e-mail. Detalhes do erro: " . $mail->ErrorInfo;
                }
            } else {
                echo "O endereço de e-mail não pertence a um usuário registrado no sistema.";
            }
        } else {
            echo "O endereço de e-mail fornecido é inválido.";
        }
    }
?>

