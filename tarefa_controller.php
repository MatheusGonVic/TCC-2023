<?php
	
	if (session_status() !== PHP_SESSION_ACTIVE) {
	    session_start();
	}

	if (isset($_SESSION['id'])) {
	    $id_usuario = $_SESSION['id'];
	}

	require_once "conexao.php";
	require "tarefa.model.php";
	require "tarefa.service.php";

	$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

	if ($acao == 'inserir') {
	    // Verifica se um arquivo foi enviado e se não houve erros no upload
	    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
	        // Define a pasta de upload
	        $pasta_upload = 'uploads/';

	        // Constrói o caminho completo do arquivo
	        $caminho_arquivo = $pasta_upload . $_FILES['arquivo']['name'];

	        // Move o arquivo da pasta temporária para a pasta de upload
	        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo)) {
	            // Se o arquivo foi enviado com sucesso, crie a tarefa
	            $tarefa = new Tarefa();
	            $tarefa->__set('tarefa', $_POST['tarefa']);
	            $tarefa->__set('data_inicio', $_POST['data_inicio']);
	            $tarefa->__set('hora_inicio', $_POST['hora_inicio']);
	            $tarefa->__set('data_termino', $_POST['data_termino']);
	            $tarefa->__set('hora_termino', $_POST['hora_termino']);
	            $tarefa->__set('prioridade', $_POST['prioridade']);
	            $tarefa->__set('arquivo', $caminho_arquivo);
	            $tarefa->__set('localizacao', $_POST['localizacao']);

	            $conexao = new Conexao();
	            $tarefa->setIdUsuario($id_usuario);

	            $tarefaService = new TarefaService($conexao, $tarefa);
	            $inserido = $tarefaService->inserir();

	            if ($inserido) {
	                header('Location: nova_tarefa.php?inclusao=1');
	                exit;
	            } else {
	                // Erro ao inserir tarefa no banco de dados
	                header('Location: nova_tarefa.php?erro=1');
	                exit;
	            }
	        } else {
	            // Erro ao mover o arquivo para a pasta de upload
	            header('Location: nova_tarefa.php?erro=1');
	            exit;
	        }
	    } else {
	        // Se nenhum arquivo foi enviado, crie a tarefa sem o arquivo
	        $tarefa = new Tarefa();
	        $tarefa->__set('tarefa', $_POST['tarefa']);
	        $tarefa->__set('data_inicio', $_POST['data_inicio']);
	        $tarefa->__set('hora_inicio', $_POST['hora_inicio']);
	        $tarefa->__set('data_termino', $_POST['data_termino']);
	        $tarefa->__set('hora_termino', $_POST['hora_termino']);
	        $tarefa->__set('prioridade', $_POST['prioridade']);
	        $tarefa->__set('localizacao', $_POST['localizacao']);

	        $conexao = new Conexao();
	        $tarefa->setIdUsuario($id_usuario);

	        $tarefaService = new TarefaService($conexao, $tarefa);
	        $inserido = $tarefaService->inserir();

	        if ($inserido) {
	            header('Location: nova_tarefa.php?inclusao=1');
	            exit;
	        } else {
	            // Erro ao inserir tarefa no banco de dados
	            header('Location: nova_tarefa.php?erro=1');
	            exit;
	        }
	    }
	} else if($acao == 'recuperar') {

		    $tarefa->__set('id_status');
	    	$tarefa->__set('id', $_SESSION['id']);
		    $tarefa = new Tarefa();

		    $conexao = new Conexao();

		    $tarefaService = new TarefaService($conexao, $tarefa);
		    $tarefas = $tarefaService->recuperar();
		
	} else if ($acao == 'atualizar') {
	    $tarefa = new Tarefa();
	    $tarefa->__set('id', $_POST['id'])
	        ->__set('tarefa', $_POST['tarefa'])
	        ->__set('data_inicio', $_POST['data_inicio'])
	        ->__set('data_termino', $_POST['data_termino'])
	        ->__set('prioridade', $_POST['prioridade'])
	        ->__set('hora_inicio', $_POST['hora_inicio']) 
	        ->__set('hora_termino', $_POST['hora_termino']) 
	        ->__set('localizacao', $_POST['localizacao']); 

	    $conexao = new Conexao();
	    $tarefaService = new TarefaService($conexao, $tarefa);

	    if ($tarefaService->atualizar()) {
	        if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
	            header('location: welcome.php');
	        } else {
	            header('location: welcome.php');
	        }
	    }
	} else if($acao == 'remover') {

		$tarefa = new Tarefa();
		$tarefa->__set('id', $_GET['id']);

		$conexao = new Conexao();

		$tarefaService = new TarefaService($conexao, $tarefa);
		$tarefaService->remover();

		if( isset($_GET['pag']) && $_GET['pag'] == 'index') {
			header('location: welcome.php');	
		} else {
			header('location: todas_tarefas.php');
		}
	
	} else if($acao == 'marcarRealizada') {

		$tarefa = new Tarefa();
		$tarefa->__set('id', $_GET['id'])->__set('id_status', 2);

		$conexao = new Conexao();

		$tarefaService = new TarefaService($conexao, $tarefa);
		$tarefaService->marcarRealizada();

		if( isset($_GET['pag']) && $_GET['pag'] == 'index') {
			header('location: index.php');	
		} else {
			header('location: todas_tarefas.php');
		}
	
	} else if($acao == 'recuperarTarefasPendentes') {
		    $tarefa = new Tarefa();
		    $tarefa->__set('id_status', 1);
		    $tarefa->__set('id', $_SESSION['id']);
		    
		    $conexao = new Conexao();

		    $tarefaService = new TarefaService($conexao, $tarefa);
		    $tarefas = $tarefaService->recuperarTarefasPendentes();
	}

?>