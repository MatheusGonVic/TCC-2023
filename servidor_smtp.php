<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    // Configuração do servidor SMTP do Gmail
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'listatarefastccmatheus@gmail.com'; // Insira aqui seu e-mail do Gmail
    $mail->Password = 'Gonvic17**'; // Insira aqui sua senha do Gmail

    // Configuração do e-mail
    $mail->setFrom('listatarefastccmatheus@gmail.com', 'Matheus');
    $mail->addAddress($to);
    $mail->Subject = 'Redefinir senha';
    $mail->Body = "Olá,\n\nPara redefinir sua senha, clique no link abaixo:\n\n" . $reset_link . "\n\nSe você não solicitou a redefinição da senha, por favor ignore este e-mail.\n\nAtenciosamente,\nSua equipe de suporte.";

    try {
        // Envio do e-mail
        $mail->send();
        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo 'Ocorreu um erro ao enviar o e-mail: ' . $mail->ErrorInfo;
    }
?>

<?php

    require_once "config.php";
    require_once "conexao.php";
    require_once "tarefa.model.php";
    require_once "tarefa.service.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    // Configuração do servidor SMTP do Gmail
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'listatarefastccmatheus@gmail.com'; // Insira aqui seu e-mail do Gmail
    $mail->Password = 'Gonvic17**'; // Insira aqui sua senha do Gmail

    // Configuração do e-mail
    $mail->setFrom('listatarefastccmatheus@gmail.com', 'Matheus');
    $mail->addAddress($to);
    $mail->Subject = 'Redefinir senha';
    $mail->Body = "Olá,\n\nPara redefinir sua senha, clique no link abaixo:\n\n" . $reset_link . "\n\nSe você não solicitou a redefinição da senha, por favor ignore este e-mail.\n\nAtenciosamente,\nSua equipe de suporte.";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];

        // Verifique se o email é válido
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $conexao = new Conexao();
            $tarefa = new Tarefa();
            $tarefaService = new TarefaService($conexao, $tarefa);

            // Verifique se o email está cadastrado no sistema
            if ($tarefaService->existeUsuario($username)) {
                // Gere um token exclusivo para o usuário
                $token = bin2hex(random_bytes(32));

                // Armazene o token no banco de dados associado ao usuário correspondente
                $tarefaService->esqueciSenha($token, $username);

                // Envie o email de redefinição de senha para o usuário
                $reset_link = "https://listatarefastccmatheus.epizy.com/reset-senha.php?token=" . $token;
                $to = $username;
                $subject = "Redefinir senha";
                $message = "Olá,\n\nPara redefinir sua senha, clique no link abaixo:\n\n" . $reset_link . "\n\nSe você não solicitou a redefinição da senha, por favor ignore este e-mail.\n\nAtenciosamente,\nSua equipe de suporte.";
                $headers = "From: matheusgoncalvesvicente@gmail.com" . "\r\n" .
                    "Reply-To: matheusgoncalvesvicente@gmail.com" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

                if (mail($to, $subject, $message, $headers)) {
                    echo "Um e-mail com as instruções para redefinir sua senha foi enviado para o endereço fornecido.";
                } else {
                    echo "Ocorreu um erro ao enviar o e-mail. Por favor, tente novamente mais tarde.";
                }
            } else {
                echo "O endereço de e-mail não pertence a um usuário registrado no sistema.";
            }
        } else {
            echo "O endereço de e-mail fornecido é inválido.";
        }
    }
?>