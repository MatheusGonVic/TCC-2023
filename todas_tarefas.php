<?php
    // Inicialize a sessão
    session_start();

    // Verifique se o usuário está logado, se não, redirecione-o para uma página de login
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    
    require_once 'conexao.php';
    require_once 'tarefa_controller.php';
    require_once 'tarefa.service.php';
    
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, new Tarefa());

    if (isset($_SESSION['id'])) {
        $id_usuario = $_SESSION['id'];
        $tarefas = $tarefaService->recuperar($id_usuario);
    } else {
        echo "ID do usuário não definido.";
    }
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>
<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Todas as tarefas</title>

		<link rel="stylesheet" href="css/estilo.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css"> 
    	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
	<body>
		<nav class="navbar navbar-light bg-light" style="margin-bottom: -58px;">
		    <div class="container">
		        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">

		            <!-- Centralize o logotipo e o título -->
		            <a class="navbar-brand d-flex align-items-center" href="login.php">
		                <img src="img/logo2.jpeg" width="40" height="40" class="d-inline-block align-top" alt="">
		                <h5 class="ml-2 mb-0">Lista Tarefas</h5>
		            </a>

		            <!-- Opções no lado direito -->
		            <div>
		                <a href="reset-password.php" class="btn custom-btn">Redefinir senha</a>
		                <a href="logout.php" class="btn custom-btn ml-2">Sair da conta</a>
		            </div>
		        </div>
		    </div>
		</nav>
		<div class="container app" style="margin-top: 70px;">
			<div class="row">
				<div class="col-md-3 menu">
					<ul class="list-group">
						<li class="list-group-item"><a href="welcome.php">Tarefas pendentes</a></li>
						<li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
						<li class="list-group-item active"><a href="#">Todas tarefas</a></li>
					</ul>
				</div>
				<div class="col-md-9">
					<div class="container pagina">
						<div class="row">
							<div class="col">
								<h4>Todas tarefas</h4>
								<br>								
								<?php if (!empty($tarefas)) { ?>
                                    <?php foreach ($tarefas as $indice => $tarefa) { ?>
                                        <div class="row mb-4 d-flex align-items-center tarefa">
                                            <div class="col-sm-9" id="tarefa_<?= $tarefa->getId() ?>">
                                                <?= $tarefa->getTarefa() ?> 
                                            </div>
											<div class="col-sm-3 mt-2 d-flex justify-content-between">
										            <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->getId() ?>, <?= $tarefa->getIdUsuario() ?>)"></i>
										            <?php if ($tarefa->getIdStatus() == 1) { ?>
										                <i class="fas fa-check-square fa-lg text-success ml-2" onclick="marcarRealizada(<?= $tarefa->getId() ?>)"></i>
										                <span class="badge badge-pill badge-info ml-2">Pendente</span>
										            <?php } elseif ($tarefa->getIdStatus() == 2) { ?>			            
										                <span class="badge badge-pill badge-success ml-2">Concluído</span>
										            <?php } ?>
										        </div>                                               
                                        </div>                                     
                                        <hr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>Você não possui nenhuma tarefa.</p>
                                <?php } ?>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="tarefa_functions.js"></script>
	</body>
</html>