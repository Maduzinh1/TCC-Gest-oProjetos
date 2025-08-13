const calendario = document.getElementById('tabela-calendario');
const mesDoAno = document.getElementById('mes-do-ano');
let hoje = new Date();
let mesAtual = hoje.getMonth(); 
let anoAtual = hoje.getFullYear();
let temporizadorSegundos = 0;
let temporizadorInterval = null;
let temporizadorAtivo = false;

function atualizarTemporizador() {
    if (temporizadorSegundos > 0) {
        temporizadorSegundos--;
        mostrarTemporizador();
        if (temporizadorSegundos === 0) {
            clearInterval(temporizadorInterval);
            temporizadorAtivo = false;
            alert('Tempo finalizado!');
        }
    }
}

function mostrarTemporizador() {
    const horas = Math.floor(temporizadorSegundos / 3600).toString().padStart(2, '0');
    const minutos = Math.floor((temporizadorSegundos % 3600) / 60).toString().padStart(2, '0');
    const segundos = (temporizadorSegundos % 60).toString().padStart(2, '0');
    document.getElementById('relogio').textContent = `${horas}:${minutos}:${segundos}`;
}

function iniciarTemporizador() {
    if (temporizadorSegundos > 0 && !temporizadorAtivo) {
        temporizadorInterval = setInterval(atualizarTemporizador, 1000);
        temporizadorAtivo = true;
    }
}

function pausarTemporizador() {
    clearInterval(temporizadorInterval);
    temporizadorAtivo = false;
}

function alternarTemporizador() {
    const btn = document.getElementById('btn-iniciar-pausar');
    if (!temporizadorAtivo) {
        iniciarTemporizador();
        btn.textContent = 'Pausar';
    } else {
        pausarTemporizador();
        btn.textContent = 'Iniciar';
    }
}

function resetarTemporizador() {
    pausarTemporizador();
    temporizadorSegundos = 0;
    mostrarTemporizador();
    document.getElementById('btn-iniciar-pausar').textContent = 'Iniciar';
}

function definirTemporizador() {
    const minutos = parseInt(prompt('Quantos minutos para o foco?', '25'));
    if (!isNaN(minutos) && minutos > 0) {
        temporizadorSegundos = minutos * 60;
        mostrarTemporizador();
    }
}

function isToday(dia, mes, ano) {
    return dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear();
}

function pegarNomeMes(mes) {
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    return meses[mes];
}

function carregarCalendarioAjax(mes, ano) {
    fetch(`./PHP/Calendario/Calendario.php?mes=${mes}&ano=${ano}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('tabela-calendario').innerHTML = html;
            document.getElementById('mes-do-ano').textContent = pegarNomeMes(mes) + ' ' + ano;
        });
}

function prevMes() {
    mesAtual--;
    if (mesAtual < 1) {
        mesAtual = 12;
        anoAtual--;
    }
    carregarCalendarioAjax(mesAtual, anoAtual);
}

function nextMes() {
    mesAtual++;
    if (mesAtual > 12) {
        mesAtual = 1;
        anoAtual++;
    }
    carregarCalendarioAjax(mesAtual, anoAtual);
}

carregarCalendarioAjax(mesAtual, anoAtual);

function fecharPopupAddProjeto() {
    document.getElementById('adicionarProjeto').style.display = 'none';
}

// Fechar o pop-up se o usuário clicar fora dele
window.onclick = function(event) {
    const popupAdicionarProjeto = document.getElementById('adicionarProjeto');
    const popupProjetosDoDia = document.getElementById('popupProjetosDoDia');
    if (event.target == popupAdicionarProjeto || event.target == popupProjetosDoDia) {
        popupAdicionarProjeto.style.display = "none";
        popupProjetosDoDia.style.display = "none";
    }
}

function abrirPopupProjetosDoDia(dataStr, event) {
    event.stopPropagation();
    let projetosDia = [];
    if (typeof projetos !== "undefined") {
        projetosDia = projetos.filter(p => p.data_inicio === dataStr);
    }
    const [ano, mes, dia] = dataStr.split('-');
    let html = `
        <div class="popup-header">
            <span class="popup-dia">${dia}/${mes}/${ano}</span>
            <span class="close" onclick="fecharPopupProjetosDoDia()">&times;</span>
        </div>
    `;
    if (projetosDia.length === 0) {
        html += "<p>Nenhum projeto neste dia.</p>";
    } else {
         html += `
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Alterar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
        `;
        projetosDia.forEach(p => {
            html += `
                <tr>
                    <td class="projet-calendario-nome">${p.nome}</td>
                    <td class="projet-calendario-descricao">${p.descricao || ''}</td>
                    <td style="text-align:center;">
                        <button class="btn-editar" onclick="alterarProjeto(${p.id}, event)" title="Alterar">✏️</button>
                    </td>
                    <td style="text-align:center;">
                        <button class="btn-excluir" onclick="excluirProjeto(${p.id})" title="Excluir">🗑️</button>
                    </td>
                </tr>
            `;
        });
        html += `
                </tbody>
            </table>
        `;
    }
    document.getElementById('popupProjetosDoDiaContent').innerHTML = html;
    document.getElementById('popupProjetosDoDia').style.display = 'flex';
}

function fecharPopupProjetosDoDia() {
    document.getElementById('popupProjetosDoDia').style.display = 'none';
}

function adicionarPojetos(event) {
    event.stopPropagation();
    // Limpa campos
    document.getElementById('eventForm').reset();
    document.getElementById('form-legend').textContent = 'Adicionar Projeto';
    const btn = document.getElementById('form-btn-projeto');
    btn.textContent = 'Adicionar Projeto';
    btn.value = 'salvar';
    document.getElementById('adicionarProjeto').style.display = 'flex';
}

function alterarProjeto(id, event) {
    event.stopPropagation();
    const projeto = projetos.find(p => p.id == id);
    if (!projeto) return;

    const form = document.querySelector('#adicionarProjeto form');
    if (!form) return;

    form.querySelector('input[name="id"]').value = projeto.id || '';
    form.querySelector('input[name="nome"]').value = projeto.nome || '';
    form.querySelector('textarea[name="descricao"]').value = projeto.descricao || '';
    form.querySelector('input[name="data_inicio"]').value = projeto.data_inicio || '';
    form.querySelector('input[name="data_fim"]').value = projeto.data_fim || '';
    form.querySelector('select[name="tag"]').value = projeto.tag || '';
    form.querySelector('select[name="status"]').value = projeto.status || '';
    form.querySelector('select[name="urgencia"]').value = projeto.urgencia || '';

    // Troca legend e botão
    document.querySelector('#adicionarProjeto legend').textContent = 'Alterar Projeto';
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.textContent = 'Salvar Alterações';
        btn.value = 'alterar';
    }

    // Exibe o popup do formulário
    document.getElementById('adicionarProjeto').style.display = 'flex';
    document.getElementById('popupProjetosDoDia').style.display = 'none';
}

function excluirProjeto(id) {
    if (confirm('Tem certeza que deseja excluir este projeto?')) {
        // Busca o projeto pelo id
        const projeto = projetos.find(p => p.id == id);
        if (!projeto) return;

        // Cria um formulário temporário para enviar via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'PHP/Projeto/Projeto.php';

        // Adiciona todos os campos do projeto como inputs hidden
        for (const campo in projeto) {
            if (projeto.hasOwnProperty(campo)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = campo;
                input.value = projeto[campo];
                form.appendChild(input);
            }
        }

        // Campo hidden para a ação
        const inputAcao = document.createElement('input');
        inputAcao.type = 'hidden';
        inputAcao.name = 'acao';
        inputAcao.value = 'excluir';
        form.appendChild(inputAcao);

        document.body.appendChild(form);
        form.submit();
    }
}