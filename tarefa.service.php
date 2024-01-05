<?php

	//CRUD
	class TarefaService {

		private $conexao;
		private $tarefa;

		public function __construct(Conexao $conexao, Tarefa $tarefa) {
			$this->conexao = $conexao->conectar();
			$this->tarefa = $tarefa;
		}

		public function inserir() {
		    $query = 'INSERT INTO tb_tarefas (id_usuario, tarefa, data_inicio, hora_inicio, data_termino, hora_termino, prioridade, arquivo, localizacao, id_status) VALUES (:id_usuario, :tarefa, :data_inicio, :hora_inicio, :data_termino, :hora_termino, :prioridade, :arquivo, :localizacao, 1)';
		    $stmt = $this->conexao->prepare($query);
		    $stmt->bindValue(':id_usuario', $_SESSION['id']);
		    $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		    $stmt->bindValue(':data_inicio', $this->tarefa->__get('data_inicio'));
		    $stmt->bindValue(':hora_inicio', $this->tarefa->__get('hora_inicio'));
		    $stmt->bindValue(':data_termino', $this->tarefa->__get('data_termino'));
		    $stmt->bindValue(':hora_termino', $this->tarefa->__get('hora_termino'));
		    $stmt->bindValue(':prioridade', $this->tarefa->__get('prioridade'));
		    $stmt->bindValue(':arquivo', $this->tarefa->__get('arquivo'));
		    $stmt->bindValue(':localizacao', $this->tarefa->__get('localizacao'));
		    
		    $sucesso = $stmt->execute();

		    if ($sucesso) {
		        return true;
		    } else {
		        return false;
		    }
		}
		
		public function recuperar($id_usuario) {
		    $tarefas = array();

		    $query = "SELECT id, id_status, tarefa, DATE_FORMAT(data_inicio, '%d/%m/%Y') as data_inicio_formatada, DATE_FORMAT(data_termino, '%d/%m/%Y') as data_termino_formatada, prioridade, arquivo, hora_inicio, hora_termino, localizacao FROM tb_tarefas WHERE id_usuario = :id_usuario AND id_status IN (1, 2) ORDER BY data_termino ASC";

		    $stmt = $this->conexao->prepare($query);
		    $stmt->bindValue(':id_usuario', $id_usuario);
		    $stmt->execute();

		    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $tarefa = new Tarefa();
		        $tarefa->setId($row['id']);
		        $tarefa->setIdUsuario($id_usuario);
		        $tarefa->setIdStatus($row['id_status']);
		        $tarefa->setTarefa($row['tarefa']);
		        $tarefa->setDataInicio($row['data_inicio_formatada']);
		        $tarefa->setDataTermino($row['data_termino_formatada']);
		        $tarefa->setPrioridade($row['prioridade']);
		        $tarefa->setArquivo($row['arquivo']); 
		        $tarefa->setHoraInicio($row['hora_inicio']); 
		        $tarefa->setHoraTermino($row['hora_termino']); 
		        $tarefa->setLocalizacao($row['localizacao']); 
		        $tarefas[] = $tarefa;
		    }

		    return $tarefas;
		}

		public function atualizar() {
		    $query = "UPDATE tb_tarefas SET tarefa = :tarefa, data_inicio = :data_inicio, data_termino = :data_termino, prioridade = :prioridade, hora_inicio = :hora_inicio, hora_termino = :hora_termino, localizacao = :localizacao WHERE id = :id";

		    $stmt = $this->conexao->prepare($query);
		    $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		    $stmt->bindValue(':data_inicio', $this->tarefa->__get('data_inicio'));
		    $stmt->bindValue(':data_termino', $this->tarefa->__get('data_termino'));
		    $stmt->bindValue(':prioridade', $this->tarefa->__get('prioridade'));
		    $stmt->bindValue(':hora_inicio', $this->tarefa->__get('hora_inicio')); 
		    $stmt->bindValue(':hora_termino', $this->tarefa->__get('hora_termino')); 
		    $stmt->bindValue(':localizacao', $this->tarefa->__get('localizacao')); 
		    $stmt->bindValue(':id', $this->tarefa->__get('id'));

		    return $stmt->execute();
		}


		public function remover() { 

			$query = 'delete from tb_tarefas where id = :id';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id', $this->tarefa->__get('id'));
			$stmt->execute();
		}

		public function marcarRealizada() { 

			$query = "update tb_tarefas set id_status = ? where id = ?";
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(1, $this->tarefa->__get('id_status'));
			$stmt->bindValue(2, $this->tarefa->__get('id'));
			return $stmt->execute(); 
		}

		public function recuperarTarefasPendentes($id_usuario) {
		    $tarefas = array();

		    $query = "SELECT id, id_status, tarefa, DATE_FORMAT(data_inicio, '%d/%m/%Y') as data_inicio_formatada, DATE_FORMAT(data_termino, '%d/%m/%Y') as data_termino_formatada, prioridade, arquivo, hora_inicio, hora_termino, localizacao FROM tb_tarefas WHERE id_usuario = :id_usuario AND id_status = 1 ORDER BY data_termino ASC";

		    $stmt = $this->conexao->prepare($query);
		    $stmt->bindValue(':id_usuario', $id_usuario);
		    $stmt->execute();

		    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $tarefa = new Tarefa();
		        $tarefa->setId($row['id']);
		        $tarefa->setIdUsuario($id_usuario);
		        $tarefa->setIdStatus($row['id_status']);
		        $tarefa->setTarefa($row['tarefa']);
		        $tarefa->setDataInicio($row['data_inicio_formatada']);
		        $tarefa->setDataTermino($row['data_termino_formatada']);
		        $tarefa->setPrioridade($row['prioridade']);
		        $tarefa->setArquivo($row['arquivo']); 
		        $tarefa->setHoraInicio($row['hora_inicio']); 
		        $tarefa->setHoraTermino($row['hora_termino']); 
		        $tarefa->setLocalizacao($row['localizacao']); 
		        $tarefas[] = $tarefa;
		    }

		    return $tarefas;
		}

		public function existeUsuario($username) {
	        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
	        $stmt = $this->conexao->prepare($query);
	        $stmt->bindValue(':username', $username);
	        $stmt->execute();

	        $result = $stmt->fetchColumn();

	        return $result > 0;
	    }

	    public function esqueciSenha($token, $username) {
	        $query = "UPDATE users SET token = :token WHERE username = :username";
	        $stmt = $this->conexao->prepare($query);
	        $stmt->bindValue(':token', $token);
	        $stmt->bindValue(':username', $username);
	        $stmt->execute();
	    }

	    public function verificarToken($token) {        
	        $sql = "SELECT id FROM users WHERE token = :token";
	        $stmt = $this->conexao->prepare($sql);
	        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
	        $stmt->execute();
	        $result = $stmt->fetch(PDO::FETCH_ASSOC);
	        return ($result !== false);
	    }

	    public function atualizarSenha($token, $new_password) {
		    // Primeiro, verifique se o token é válido
		    if ($this->verificarToken($token)) {
		        // Criptografe a nova senha
		        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

		        // Atualize a senha criptografada no banco de dados para o usuário associado ao token
		        $sql = "UPDATE users SET password = :password WHERE token = :token";
		        $stmt = $this->conexao->prepare($sql);
		        $stmt->bindValue(':password', $hashed_password);
		        $stmt->bindValue(':token', $token);
		        $stmt->execute();
		    }
		}

		public function atualizarTarefas($id_usuario, $ordenarPor) {
		    $tarefas = array();
		    $ordenacao = 'data_termino ASC'; // Padrão: ordenar por data de término ascendente

		    if ($ordenarPor === 'prioridade') {
		        $ordenacao = 'prioridade DESC, data_termino ASC'; // Ordenar por prioridade descendente e data de término ascendente
		    } elseif ($ordenarPor === 'data_inicio') {
		        $ordenacao = 'data_inicio ASC'; // Ordenar por data de início ascendente
		    }

		    $query = "SELECT id, id_status, tarefa, DATE_FORMAT(data_inicio, '%d/%m/%Y') as data_inicio_formatada, DATE_FORMAT(data_termino, '%d/%m/%Y') as data_termino_formatada, prioridade, arquivo, hora_inicio, hora_termino, localizacao FROM tb_tarefas WHERE id_usuario = :id_usuario AND id_status = 1 ORDER BY $ordenacao";

		    $stmt = $this->conexao->prepare($query);
		    $stmt->bindValue(':id_usuario', $id_usuario);
		    $stmt->execute();

		    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $tarefa = new Tarefa();
		        $tarefa->setId($row['id']);
		        $tarefa->setIdUsuario($id_usuario);
		        $tarefa->setIdStatus($row['id_status']);
		        $tarefa->setTarefa($row['tarefa']);
		        $tarefa->setDataInicio($row['data_inicio_formatada']);
		        $tarefa->setDataTermino($row['data_termino_formatada']);
		        $tarefa->setPrioridade($row['prioridade']);
		        $tarefa->setArquivo($row['arquivo']);
		        $tarefa->setHoraInicio($row['hora_inicio']);
		        $tarefa->setHoraTermino($row['hora_termino']);
		        $tarefa->setLocalizacao($row['localizacao']);
		        $tarefas[] = $tarefa;
		    }

		    return $tarefas;
		}

	}
?>

