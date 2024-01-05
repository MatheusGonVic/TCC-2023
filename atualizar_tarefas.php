<?php
// Inicialize a sessão
session_start();

require_once 'conexao.php';
require_once 'tarefa.service.php';
require_once 'tarefa.model.php';

if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
    $ordenarPor = $_GET['ordenarPor']; // Receba o critério de ordenação da solicitação AJAX

    $conexao = new Conexao(); // Conecta ao Banco de dados
    $tarefaService = new TarefaService($conexao, new Tarefa()); // Manipula informações das tarefas

    // Recupere as tarefas atualizadas com base no critério de ordenação
    $tarefas = $tarefaService->atualizarTarefas($id_usuario, $ordenarPor);

    if (!empty($tarefas)) {
        foreach ($tarefas as $indice => $tarefa) {
            ?>
            <div class="tarefa">
                <div class="row mb-4 d-flex align-items-center">
                    <div class="col-sm-9 tarefa-titulo" onclick="toggleTarefaInfo(<?= $tarefa->getId() ?>)"><?= $tarefa->getTarefa() ?></div>
                    <div class="col-sm-3 mt-2 d-flex justify-content-between">
                        <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->getId() ?>)"></i>
                        <i class="fas fa-edit fa-lg text-info" onclick="editar(<?= $tarefa->getId() ?>, '<?= $tarefa->getTarefa() ?>')"></i>
                        <i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->getId() ?>)"></i>
                    </div>
                </div>
                <div class="tarefa-info hidden" id="tarefa_info_<?= $tarefa->getId() ?>">
                    <div class="row mb-4 d-flex align-items-center">
                        <div class="col-sm-4">
                            <small><b>Data de início:</b></small>
                            <small><?= $tarefa->getDataInicio() ?></small>
                        </div>
                        <div class="col-sm-4">
                            <small><b>Hora de início:</b></small>
                            <small><?= $tarefa->getHoraInicio() ?></small>
                        </div>
                        <div class="col-sm-4">
                            <small><b>Prioridade:</b></small>
                            <small>
                                <?php
                                switch ($tarefa->getPrioridade()) {
                                    case 1:
                                        echo 'Baixa';
                                        break;
                                    case 2:
                                        echo 'Média';
                                        break;
                                    case 3:
                                        echo 'Alta';
                                        break;
                                    default:
                                        echo 'Desconhecida';
                                        break;
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-4 d-flex align-items-center">
                        <div class="col-sm-4">
                            <small><b>Data de término:</b></small>
                            <small><?= $tarefa->getDataTermino() ?></small>
                        </div>
                        <div class="col-sm-4">
                            <small><b>Hora de término:</b></small>
                            <small><?= $tarefa->getHoraTermino() ?></small>
                        </div>
                        <div class="col-sm-4">
                            <?php if (!empty($tarefa->getArquivo())) { ?>
                                <a href="<?= $tarefa->getArquivo() ?>" target="_blank" class="btn custom-btn">Download Arquivo</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-4 d-flex align-items-center">
                        <div class="col-sm-5">
                            <small><b>Localização:</b></small>
                            <small><?= $tarefa->getLocalizacao() ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php
        }
    } else {
        echo "<p>Nenhuma tarefa pendente encontrada.</p>";
    }
} else {
    echo "ID do usuário não definido.";
}

?>