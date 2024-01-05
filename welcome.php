<?php
    // Inicialize a sessão
    session_start();

    // Verifica se o usuário está logado, se não, redirecione-o para uma página de login.
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    
    require_once 'conexao.php';
    require_once 'tarefa.service.php';
    require_once 'tarefa.model.php';

    $conexao = new Conexao(); // Conecta ao Banco de dados
    $tarefaService = new TarefaService($conexao, new Tarefa()); // Manipula informações das tarefas

    if (isset($_SESSION['id'])) {
        $id_usuario = $_SESSION['id'];
        $tarefas = $tarefaService->recuperarTarefasPendentes($id_usuario);
    } else {
        echo "ID do usuário não definido.";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tarefas Pendentes</title>

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
                <li class="list-group-item active"><a href="#">Tarefas pendentes</a></li>
                <li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
                <li class="list-group-item"><a href="todas_tarefas.php">Todas tarefas</a></li>
            </ul>
        </div>
            <div class="col-md-9">
                <div class="container pagina">
                    <div class="row">
                        <div class="col">
                            <h4 id="tituloTarefas" class="d-flex justify-content-between">Tarefas pendentes
                                <form method="post" action="welcome.php" class="form-inline">
                                    <select name="ordenarPor" id="ordenarPor" class="form-control mr-2" onchange="atualizarTarefas()">
                                        <option value="data_termino">Data de Término</option>
                                        <option value="data_inicio">Data de Início</option>
                                        <option value="prioridade">Prioridade</option>
                                    </select>
                                </form>
                            </h4>
                            <br>
                            <div id="tarefasContainer">
                                <?php if (!empty($tarefas)) { ?>
                                    <?php foreach ($tarefas as $indice => $tarefa) { ?>
                                        <div class="tarefa" id="tarefa_<?= $tarefa->getId() ?>">
                                            <div class="row mb-4 d-flex align-items-center">
                                                <div class="col-sm-9 tarefa-titulo" onclick="toggleTarefaInfo(<?= $tarefa->getId() ?>)"><?= $tarefa->getTarefa() ?></div>
                                                <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                                    <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->getId() ?>)"></i>
                                                    <i class="fas fa-edit fa-lg text-info" onclick="editar(
                                                        <?= $tarefa->getId() ?>,'<?= $tarefa->getTarefa() ?>',
                                                        '<?= $tarefa->getDataInicio() ?>','<?= $tarefa->getDataTermino() ?>','<?= $tarefa->getHoraInicio() ?>','<?= $tarefa->getHoraTermino() ?>','<?= $tarefa->getPrioridade() ?>','<?= $tarefa->getLocalizacao() ?>')"></i>
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
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>Nenhuma tarefa pendente encontrada.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="tarefa_functions.js"></script>
    <script src="ajax_functions.js"></script>
</body>
</html>

