
function atualizarTarefas() {
    var ordenarPor = document.getElementById('ordenarPor').value;

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Log da resposta do servidor
            console.log(xhr.responseText);

            var tarefasContainer = document.getElementById('tarefasContainer');
            tarefasContainer.innerHTML = xhr.responseText;
        }
    };

    xhr.open('GET', 'atualizar_tarefas.php?ordenarPor=' + ordenarPor, true);
    xhr.send();
}
