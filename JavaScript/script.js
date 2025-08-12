const calendario = document.getElementById('tabela-calendario');
const mesDoAno = document.getElementById('mes-do-ano');
let hoje = new Date();
let mesAtual = hoje.getMonth(); 
let anoAtual = hoje.getFullYear();

function atualizarRelogio() {
    const horas = hoje.getHours().toString().padStart(2, '0');
    const minutos = hoje.getMinutes().toString().padStart(2, '0');
    const relogio = document.getElementById('relogio');
    relogio.textContent = `${horas}:${minutos}`;
}

setInterval(atualizarRelogio, 1000);
atualizarRelogio();

function isToday(dia, mes, ano) {
    return dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear();
}

function pegarNomeMes(mes) {
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    return meses[mes];
}

function carregarCalendario(mes, ano) {
    const primeiroDia = new Date(ano, mes, 1).getDay();
    const diasNoMes = new Date(ano, mes + 1, 0).getDate();
    const diasDaSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    let html = '<tr>' + diasDaSemana.map(d => `<th>${d}</th>`).join('') + '</tr>';

    let dia = 1;
    // 6 semanas (linhas) garantidas
    for (let semana = 0; semana < 6; semana++) {
        html += '<tr>';
        for (let i = 0; i < 7; i++) {
            const cellIndex = semana * 7 + i;
            if (semana === 0 && i < primeiroDia) {
                html += '<td></td>';
            } else if (dia > diasNoMes) {
                html += '<td></td>';
            } else {
                // Formato da data igual ao do banco (YYYY-MM-DD)
                const dataStr = `${ano}-${String(mes+1).padStart(2,'0')}-${String(dia).padStart(2,'0')}`;
                const isHoje = isToday(dia, mes, ano);
                // Filtra projetos para o dia
                let projetosDia = [];
                if (typeof projetos !== "undefined") {
                    projetosDia = projetos.filter(p => p.data_inicio === dataStr);
                }
                let projetosHtml = '';
                if (projetosDia.length > 3) {
                    projetosHtml += projetosDia.slice(0,2).map(p =>
                        `<div class="projeto-calendario">${p.nome}</div>`
                    ).join('');
                    projetosHtml += `<div class="projeto-mais">Mais...</div>`;
                } else {
                    projetosHtml += projetosDia.map(p =>
                        `<div class="projeto-calendario">${p.nome}</div>`
                    ).join('');
                }
                let cellClasses = [];
                if (isHoje) {
                    cellClasses.push('today');
                }
                if (projetosDia.length > 0) {
                    cellClasses.push('dia-com-projeto');
                }
                html += `<td style="position:relative;" class="${cellClasses.join(' ')}" onclick="abrirPopupProjetosDoDia('${dataStr}', event)">
                            <span class="numero-dia">${dia}</span>
                            ${projetosHtml}
                            <button class="add-btn" onclick="adicionarPojetos(event); event.stopPropagation();" title="Adicionar evento"><span>+</span></button>
                        </td>`;
                dia++;
            }
        }
        html += '</tr>';
    }
    calendario.innerHTML = html;
    mesDoAno.textContent = `${pegarNomeMes(mes)} ${ano}`;
}

function prevMes() {
    mesAtual--;
    if (mesAtual < 0) {
        mesAtual = 11;
        anoAtual--;
    }
    carregarCalendario(mesAtual, anoAtual);
}

function nextMes() {
    mesAtual++;
    if (mesAtual > 11) {
        mesAtual = 0;
        anoAtual++;
    }
    carregarCalendario(mesAtual, anoAtual);
}

carregarCalendario(mesAtual, anoAtual);

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