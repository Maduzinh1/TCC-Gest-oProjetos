const calendario = document.getElementById('tabela-calendario');
const mesDoAno = document.getElementById('mes-do-ano');
let hoje = new Date();
let mesAtual = hoje.getMonth(); 
let anoAtual = hoje.getFullYear();
let temporizadorSegundos = 0;
let temporizadorInterval = null;
let temporizadorAtivo = false;


//Temporizador
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
    document.getElementById('temporizador').textContent = `${horas}:${minutos}:${segundos}`;
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
        btn.className = 'pausar-tempo';
    } else {
        pausarTemporizador();
        btn.className = 'iniciar-tempo';
    }
}

function resetarTemporizador() {
    pausarTemporizador();
    temporizadorSegundos = 0;
    mostrarTemporizador();
    document.getElementById('btn-iniciar-pausar').className = 'iniciar-tempo';
}

function definirTemporizador() {
    const minutos = parseInt(prompt('Quantos minutos para o foco?', '25'));
    if (!isNaN(minutos) && minutos > 0) {
        temporizadorSegundos = minutos * 60;
        mostrarTemporizador();
    }
}
//Fim temporizador

//Calendário
function isToday(dia, mes, ano) {
    return dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear();
}

function pegarNomeMes(mes) {
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    return meses[mes];
}

function carregarCalendarioAjax(mes, ano) {
    fetch(`./PHP/Calendario/GerarCalendario.php?mes=${mes}&ano=${ano}`)
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
//Fim calendário


function fecharPopupAddItem() {
    document.getElementById('adicionarItem').style.display = 'none';
}

// Fechar o pop-up se o usuário clicar fora dele
window.onclick = function(event) {
    const popupAdicionarItem = document.getElementById('adicionarItem');
    const popupItemsDoDia = document.getElementById('popupItemsDoDia');
    if (event.target == popupAdicionarItem || event.target == popupItemsDoDia) {
        popupAdicionarItem.style.display = "none";
        popupItemsDoDia.style.display = "none";
    }
}

function abrirPopupItemsDoDia(dataStr, event) {
    event.stopPropagation();
    let itemsDia = [];
    if (typeof items !== "undefined") {
        itemsDia = items.filter(i => i.data_inicio === dataStr);
    }
    const [ano, mes, dia] = dataStr.split('-');
    let html = `
        <div class="popup-header">
            <span class="popup-dia">${dia}/${mes}/${ano}</span>
            <span class="close" onclick="fecharPopupItemsDoDia()">&times;</span>
        </div>
    `;
    if (itemsDia.length === 0) {
        html += "<p>Nenhum item neste dia.</p>";
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
        itemsDia.forEach(i => {
            html += `
                <tr>
                    <td class="item-calendario-nome">${i.nome}</td>
                    <td class="item-calendario-descricao">${i.descricao || ''}</td>
                    <td style="text-align:center;">
                        <button class="btn-editar" onclick="alterarItem(${i.id}, event)" title="Alterar">✏️</button>
                    </td>
                    <td style="text-align:center;">
                        <button class="btn-excluir" onclick="excluirItem(${i.id})" title="Excluir">🗑️</button>
                    </td>
                </tr>
            `;
        });
        html += `
                </tbody>
            </table>
        `;
    }
    document.getElementById('popupItemsDoDiaContent').innerHTML = html;
    document.getElementById('popupItemsDoDia').style.display = 'flex';
}

function fecharPopupItemsDoDia() {
    document.getElementById('popupItemsDoDia').style.display = 'none';
}

function adicionarItem(event) {
    event.stopPropagation();
    // Limpa campos
    document.getElementById('eventForm').reset();
    document.getElementById('form-legend').textContent = 'Adicionar Item';
    const btn = document.getElementById('form-btn-item');
    btn.textContent = 'Adicionar Item';
    btn.value = 'salvar';
    document.getElementById('adicionarItem').style.display = 'flex';
}

function alterarItem(id, event) {
    event.stopPropagation();
    const item = items.find(i => i.id == id);
    if (!item) return;

    const form = document.querySelector('#adicionarItem form');
    if (!form) return;

    form.querySelector('input[name="id"]').value = item.id || '';
    form.querySelector('input[name="nome"]').value = item.nome || '';
    form.querySelector('textarea[name="descricao"]').value = item.descricao || '';
    form.querySelector('input[name="data_inicio"]').value = item.data_inicio || '';
    form.querySelector('input[name="data_fim"]').value = item.data_fim || '';
    form.querySelector('select[name="tag"]').value = item.tag || '';
    form.querySelector('select[name="status"]').value = item.status || '';
    form.querySelector('select[name="urgencia"]').value = item.urgencia || '';

    // Troca legend e botão
    document.querySelector('#adicionarItem legend').textContent = 'Alterar Item';
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.textContent = 'Salvar Alterações';
        btn.value = 'alterar';
    }

    // Exibe o popup do formulário
    document.getElementById('adicionarItem').style.display = 'flex';
    document.getElementById('popupItemsDoDia').style.display = 'none';
}

function excluirItem(id) {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        // Busca o item pelo id
        const item = items.find(p => p.id == id);
        if (!item) return;

        // Cria um formulário temporário para enviar via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'PHP/Calendario/Calendario.php';

        // Adiciona todos os campos do item como inputs hidden
        for (const campo in item) {
            if (item.hasOwnProperty(campo)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = campo;
                input.value = item[campo];
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