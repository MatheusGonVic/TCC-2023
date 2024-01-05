
function editar(id, txt_tarefa, data_inicio, data_termino, hora_inicio, hora_termino, prioridade, localizacao, ordenarPor) {
    
    // Criar um form de edição
    let form = document.createElement('form');
    form.action = 'tarefa_controller.php?acao=atualizar';
    form.method = 'post';
    form.className = 'row';

    // Criar um input para entrada do texto
    let inputTarefa = document.createElement('input');
    inputTarefa.type = 'text';
    inputTarefa.name = 'tarefa';
    inputTarefa.className = 'col-11 form-control';
    inputTarefa.value = txt_tarefa;

    // Criar um input hidden para guardar o id da tarefa
    let inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'id';
    inputId.value = id;

    // Incluir inputTarefa no form
    form.appendChild(inputTarefa);

    // Incluir inputId no form
    form.appendChild(inputId);

    // Criar inputs para edição da data de início
    let inputDataInicio = document.createElement('input');
    inputDataInicio.type = 'date';
    inputDataInicio.name = 'data_inicio';
    inputDataInicio.className = 'col-3 form-control mr-5';
    inputDataInicio.value = data_inicio;
    inputDataInicio.style.marginRight = '50px';
    inputDataInicio.style.marginTop = '20px';

    // Criar inputs para edição da data de término
    let inputDataTermino = document.createElement('input');
    inputDataTermino.type = 'date';
    inputDataTermino.name = 'data_termino';
    inputDataTermino.className = 'col-3 form-control mr-5';
    inputDataTermino.value = data_termino;
    inputDataTermino.style.marginRight = '50px';
    inputDataTermino.style.marginTop = '20px';

    // Criar inputs para edição da hora de início
    let inputHoraInicio = document.createElement('input');
    inputHoraInicio.type = 'time';
    inputHoraInicio.name = 'hora_inicio';
    inputHoraInicio.className = 'col-3 form-control mr-5';
    inputHoraInicio.value = hora_inicio;
    inputHoraInicio.style.marginRight = '50px';
    inputHoraInicio.style.marginTop = '20px';

    // Criar inputs para edição da hora de término
    let inputHoraTermino = document.createElement('input');
    inputHoraTermino.type = 'time';
    inputHoraTermino.name = 'hora_termino';
    inputHoraTermino.className = 'col-3 form-control';
    inputHoraTermino.value = hora_termino;
    inputHoraTermino.style.marginRight = '50px';
    inputHoraTermino.style.marginTop = '20px';

    // Criar inputs para edição da localização
    let inputLocalizacao = document.createElement('input');
    inputLocalizacao.type = 'text';
    inputLocalizacao.name = 'localizacao';
    inputLocalizacao.className = 'col-3 form-control';
    inputLocalizacao.value = localizacao;
    inputLocalizacao.style.marginTop = '20px';

    // Criar inputs para edição da prioridade
    let selectPrioridade = document.createElement('select');
    selectPrioridade.name = 'prioridade';
    selectPrioridade.className = 'col-3 form-control';
    selectPrioridade.style.marginTop = '20px';

    // Criar opções da prioridade
    let optionBaixa = document.createElement('option');
    optionBaixa.value = '1';
    optionBaixa.innerHTML = 'Baixa';
    let optionMedia = document.createElement('option');
    optionMedia.value = '2';
    optionMedia.innerHTML = 'Média';
    let optionAlta = document.createElement('option');
    optionAlta.value = '3';
    optionAlta.innerHTML = 'Alta';

    // Definir opção selecionada com base no valor atual da prioridade
    switch (prioridade) {
        case '1':
            optionBaixa.selected = true;
            break;
        case '2':
            optionMedia.selected = true;
            break;
        case '3':
            optionAlta.selected = true;
            break;
    }

    // Adicionar opções ao selectPrioridade
    selectPrioridade.appendChild(optionBaixa);
    selectPrioridade.appendChild(optionMedia);
    selectPrioridade.appendChild(optionAlta);

    // Adicionar inputs de edição no form
    form.appendChild(inputDataInicio);
    form.appendChild(inputDataTermino);
    form.appendChild(selectPrioridade);
    form.appendChild(inputHoraInicio);
    form.appendChild(inputHoraTermino);
    form.appendChild(inputLocalizacao);

    // Criar um button para envio do form (Atualizar)
    let button = document.createElement('button');
    button.type = 'submit';
    button.className = 'col-5 btn btn-info mt-3 mr-5';
    button.innerHTML = 'Atualizar';

    // Criar botão para cancelar
    let buttonCancelar = document.createElement('button');
    buttonCancelar.type = 'button';
    buttonCancelar.className = 'col-5 btn btn-danger mt-3';
    buttonCancelar.innerHTML = 'Cancelar';
    buttonCancelar.addEventListener('click', function () {
        // Voltar para a página de tarefas com a ordenação atual
        window.location.href = 'welcome.php?ordenarPor=' + ordenarPor;
    });

    // Incluir botões no form
    form.appendChild(button);
    form.appendChild(buttonCancelar);

    // Selecionar a div tarefa
    let tarefa = document.getElementById('tarefa_' + id);

    // Limpar o texto da tarefa para inclusão do form
    tarefa.innerHTML = '';

    // Incluir form na página
    tarefa.insertBefore(form, tarefa[0]);
}

function remover(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você não será capaz de reverter isso!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apagar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmou, redirecione para a ação de remoção
            location.href = 'todas_tarefas.php?acao=remover&id=' + id;
        }
    });
}

function marcarRealizada(id) {
    // SweetAlert2 para exibir uma caixa de diálogo
    Swal.fire({
        title: 'Marcar como realizada?',
        text: 'Você tem certeza de que deseja marcar esta tarefa como realizada?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, marcar como realizada!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmou, redirecione para a ação de marcação como realizada
            location.href = 'todas_tarefas.php?acao=marcarRealizada&id=' + id;
        }
    });
}

// Função para abrir e fechar informações das tarefas
function toggleTarefaInfo(tarefaId) {
    var tarefaInfo = document.getElementById('tarefa_info_' + tarefaId);
    if (tarefaInfo.style.display === 'none' || tarefaInfo.style.display === '') {
        tarefaInfo.style.display = 'block';
    } else {
        tarefaInfo.style.display = 'none';
    }
}
