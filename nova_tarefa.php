<?php
    // Inicialize a sessão
    session_start();
     
    // Verifique se o usuário está logado, se não, redirecione-o para uma página de login
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    require_once 'conexao.php';
    require_once 'config.php';
    require_once 'tarefa.service.php';
    require_once 'tarefa.model.php';
    
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, new Tarefa());
    
    if (isset($_SESSION['id_usuario'])) {
        $tarefas = $tarefaService->recuperarTarefasPendentes($_SESSION['id_usuario']);
    }
?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nova tarefa</title>

        <link rel="stylesheet" href="css/estilo.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    </head>

    <body>
    	<script>
		    const textarea = document.querySelector('.expandable');
		    textarea.addEventListener('input', function () {
		        this.style.height = 'auto';
		        this.style.height = (this.scrollHeight) + 'px';
		    });
		</script>
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
			<?php if( isset($_GET['inclusao']) && $_GET['inclusao'] == 1 ) { ?>
					<div class="btn custom-btn pt-3 d-flex justify-content-center mt-3" style="background-color: #59E4A8; width: 100%;">
					    <h5>Tarefa inserida com sucesso!</h5>
					</div>
			<?php } ?>
		</div>
		<div class="container app" style="margin-top: 10px;">
			<div class="row">
				<div class="col-md-3 menu">
					<ul class="list-group">
						<li class="list-group-item"><a href="welcome.php">Tarefas pendentes</a></li>
						<li class="list-group-item active"><a href="#">Nova tarefa</a></li>
						<li class="list-group-item"><a href="todas_tarefas.php">Todas tarefas</a></li>
					</ul>
				</div>
				<div class="col-md-9">
				    <div class="container pagina">
				        <div class="row">
				            <div class="col">
				                <h4>Nova tarefa</h4>
				                <br>
				                <div class="row">
				                    <div class="col-md-12">
				                        <form method="post" action="tarefa_controller.php?acao=inserir" enctype="multipart/form-data">
										    <div class="form-group">
										        <label for="tarefa">Descrição da tarefa:</label>
										        <textarea id="tarefa" class="form-control expandable" name="tarefa" placeholder="Exemplo: Trabalho de Sistemas Operacionais I" required></textarea>
										    </div>
										    <div class="form-group d-flex">
										        <div class="form-group d-flex flex-column gap-3">
										            <label for="prioridade">Prioridade:</label>
										            <select name="prioridade" id="prioridade" class="form-control">
										                <option value="1">Baixa</option>
										                <option value="2">Média</option>
										                <option value="3">Alta</option>
										            </select>
										        </div>
										        <div class="form-group d-flex flex-column gap-3 ml-5">
										            <label for="arquivo">Carregar Arquivo:</label>
										            <input type="file" id="arquivo" name="arquivo" accept=".pdf, .jpg, .jpeg">
										        </div>
										        <div class="form-group ml-5">
												    <label for="add_location">Adicionar Localização?</label>
												    <select id="add_location" class="form-control" onchange="toggleMapAndLocation()">
												        <option value="no">Não</option>
												        <option value="yes">Sim</option>
												    </select>
												</div>
										    </div>
										    <div class="form-group d-flex gap-3">
										        <div>
										            <label for="data_inicio">Data de Início:</label>
										            <input type="date" id="data_inicio" name="data_inicio" class="form-control">
										        </div>
										        <div class="ml-5">
										            <label for="hora_inicio">Hora de Início:</label>
										            <input type="time" id="hora_inicio" name="hora_inicio" class="form-control">
										        </div>
										        <div class="ml-5">
										            <label for="data_termino">Data de Término:</label>
										            <input type="date" id="data_termino" name="data_termino" class="form-control">
										        </div>
										        <div class="ml-5">
										            <label for="hora_termino">Hora de Término:</label>
										            <input type="time" id="hora_termino" name="hora_termino" class="form-control">
										        </div>
										    </div>
												<div id="map-container" style="display: none;">
												    <div id="map" style="height: 300px;"></div>
												</div><p></p>
												<div id="location-container" style="display: none;">
												    <label for="localizacao">Localização:</label>
												    <input type="text" id="localizacao" name="localizacao" class="form-control" placeholder="Digite o endereço">
												</div>
											<button class="btn custom-btn col-12 mt-3" type="submit">Cadastrar</button>
											<input type="hidden" id="latitude" name="latitude">
											<input type="hidden" id="longitude" name="longitude">
										</form>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>

		<script src="map_functions.js"></script>

	</body>
</html>