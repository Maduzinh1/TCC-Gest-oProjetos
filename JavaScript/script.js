const calendario = document.getElementById('tabela-calendario');
const mesDoAno = document.getElementById('mes-do-ano');
let hoje = new Date();
let mesAtual = hoje.getMonth(); 
let anoAtual = hoje.getFullYear();
let temporizadorSegundos = 0;
let temporizadorInterval = null;
let temporizadorAtivo = false;


// Temporizador
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
    const i = document.getElementById('btn-iniciar-pausar').querySelector('i');
    if (!temporizadorAtivo) {
        iniciarTemporizador();
        i.className = 'fa-solid fa-pause';
    } else {
        pausarTemporizador();
        i.className = 'fa-solid fa-play';
    }
}

function resetarTemporizador() {
    pausarTemporizador();
    temporizadorSegundos = 0;
    mostrarTemporizador();
    document.getElementById('btn-iniciar-pausar').querySelector('i').className = 'fa-solid fa-play';
}

function definirTemporizador() {
    const minutos = parseInt(prompt('Quantos minutos para o foco?', '25'));
    if (!isNaN(minutos) && minutos > 0) {
        temporizadorSegundos = minutos * 60;
        mostrarTemporizador();
    }
}
// Fim temporizador

// Botões para trocar a tela de calendário pela de tags
document.getElementById('btn-calendario').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('Bloco-calendario').style.display = 'flex';
    document.getElementById('Bloco-tags').style.display = 'none';
});

document.getElementById('btn-tags').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('Bloco-calendario').style.display = 'none';
    document.getElementById('Bloco-tags').style.display = 'flex';
    carregarTagsAjax();
});
// Fim botões para trocar a tela de calendário pela de tags

// Calendário
function isToday(dia, mes, ano) {
    return dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear();
}

function pegarNomeMes(mes) {
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    return meses[mes];
}

function carregarCalendarioAjax(mes, ano) {
    fetch(`./gerarCalendario.php?mes=${mes}&ano=${ano}`)
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
// Fim calendário

// Tags
function carregarTagsAjax() {
    fetch('./gerarTags.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('tabela-tags').innerHTML = html;
        });
}
// Fim tags

// Pastas
function carregarPastasAjax() {
    fetch('./gerarPastas.php')
        .then(response => response.text())
        .then(html => {
            document.querySelector('.Pastas-conteudo').innerHTML = html;
        });
}

carregarPastasAjax();

document.getElementById('imagem-pasta').addEventListener('change', function() {
    const nome = this.files[0] ? this.files[0].name : '';
    const label = document.getElementById('imagem-pasta-label');
    if (nome) {
      label.textContent = nome;
      label.classList.add('selected');
    } else {
        label.innerHTML = '<i class="fa-solid fa-image"></i> Escolher imagem';
        label.classList.remove('selected');
    }
});
//Fim pastas

function abrirPopupAddItem(event) {
    if (event) {
        event.stopPropagation();
    }
    // Limpa o formulário para novo item
    const form = document.querySelector('#adicionarItem form');
    if (form) {
        form.reset();
        form.querySelector('input[name="id"]').value = '';
        form.querySelector('input[name="nome"]').value = '';
        form.querySelector('textarea[name="descricao"]').value = '';
        form.querySelector('input[name="data_inicio"]').value = '';
        form.querySelector('input[name="data_fim"]').value = '';
        form.querySelector('select[name="status"]').selectedIndex = 0;
        form.querySelector('select[name="urgencia"]').selectedIndex = 0;
        document.querySelector('#adicionarItem legend').textContent = 'Adicionar Item';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.textContent = 'Adicionar item';
            btn.value = 'adicionar';
        }
    }
    if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('id');
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
    document.getElementById('adicionarItem').style.display = 'flex';
}

function fecharPopupAddItem() {
    document.getElementById('adicionarItem').style.display = 'none';
}

function abrirPopupAddTag(event) {
    if (event) {
        event.stopPropagation();
    }
    // Limpa o formulário para nova tag
    const form = document.querySelector('#adicionarTag form');
    if (form) {
        form.reset();
        form.querySelector('input[name="id"]').value = '';
        document.querySelector('#adicionarTag legend').textContent = 'Adicionar Tag';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.textContent = 'Adicionar Tag';
            btn.value = 'adicionar';
        }
    }
    document.getElementById('adicionarTag').style.display = 'flex';
}

function fecharPopupAddTag() {
    document.getElementById('adicionarTag').style.display = 'none';
}

function abrirPopupAddPasta(event) {
    if (event) event.stopPropagation();
    const form = document.querySelector('#adicionarPasta form');
    if (form) {
        form.reset();
        form.querySelector('input[name="id"]').value = '';
        document.querySelector('#adicionarPasta legend').textContent = 'Adicionar Pasta';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.textContent = 'Adicionar pasta';
            btn.value = 'adicionar';
        }
    }
    document.getElementById('adicionarPasta').style.display = 'flex';
}

function fecharPopupAddPasta() {
    document.getElementById('adicionarPasta').style.display = 'none';
}

function abrirPopupAddProjeto(event) {
    if (event) event.stopPropagation();
    const form = document.querySelector('#adicionarProjeto form');
    if (form) {
        form.reset();
        form.querySelector('input[name="id"]').value = '';
        document.querySelector('#adicionarProjeto legend').textContent = 'Adicionar Projeto';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.textContent = 'Adicionar projeto';
            btn.value = 'adicionar';
        }
    }
    document.getElementById('adicionarProjeto').style.display = 'flex';
}

function fecharPopupAddProjeto() {
    document.getElementById('adicionarProjeto').style.display = 'none';
}

function abrirPopupAddCard(status, idProjeto) {
    const form = document.querySelector('#adicionarCard form');
    if (form) {
        form.reset();
        document.getElementById('idProjeto-card').value = idProjeto;
        document.getElementById('status-card').value = status;
        document.getElementById('form-legend-card').textContent = 'Adicionar Card';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.textContent = 'Adicionar card';
            btn.value = 'adicionar';
        }
    }
    document.getElementById('adicionarCard').style.display = 'flex';
}

function fecharPopupAddCard() {
    document.getElementById('adicionarCard').style.display = 'none';
}

function abrirPopupCard(idCard, idProjeto, event) {
    if (event) event.stopPropagation();
    fetch(`./../Controller/QuadroProjetoController.php?acao=ver&idCard=${idCard}&idProjeto=${idProjeto}`)
        .then(response => response.text())
        .then(html => {
            const content = document.getElementById('popupCardContent');
            if (content) {
                content.innerHTML = html;
            }
            const popup = document.getElementById('popupCard');
            if (popup) {
                popup.style.display = 'flex';
            }
        })
        .catch(() => {
            const content = document.getElementById('popupCardContent');
            if (content) {
                content.innerHTML = '<div class="popup-header-dia"><h3>Erro</h3></div><div class="items-dia">Não foi possível carregar o card.</div>';
            }
            const popup = document.getElementById('popupCard');
            if (popup) {
                popup.style.display = 'flex';
            }
        });
}

function fecharPopupCard() {
    const popup = document.getElementById('popupCard');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Fechar o pop-up se o usuário clicar fora dele
window.onclick = function(event) {
    const popupAdicionarItem = document.getElementById('adicionarItem');
    const popupAdicionarTag = document.getElementById('adicionarTag');
    const popupItemsDoDia = document.getElementById('popupItemsDoDia');
    const popupAdicionarPasta = document.getElementById('adicionarPasta');
    const popupAdicionarProjeto = document.getElementById('adicionarProjeto');
    const popupAdicionarCard = document.getElementById('adicionarCard');
    const popupCard = document.getElementById('popupCard');
    if (event.target == popupAdicionarItem || event.target == popupItemsDoDia || event.target == popupAdicionarTag || event.target == popupAdicionarPasta || event.target == popupAdicionarProjeto || event.target == popupAdicionarCard || event.target == popupCard) {
        popupAdicionarItem.style.display = "none";
        popupItemsDoDia.style.display = "none";
        popupAdicionarTag.style.display = "none";
        popupAdicionarPasta.style.display = "none";
        popupAdicionarProjeto.style.display = "none";
        popupAdicionarCard.style.display = "none";
        popupCard.style.display = "none";
    }
}

function abrirPopupItemsDoDia(dataStr, event) {
    event.stopPropagation();
    fetch(`./../Controller/CalendarioController.php?acao=itens-do-dia&data=${dataStr}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('popupItemsDoDiaContent').innerHTML = html;
            document.getElementById('popupItemsDoDia').style.display = 'flex';
        });
}

function fecharPopupItemsDoDia() {
    document.getElementById('popupItemsDoDia').style.display = 'none';
}

// Mostra o popup de edição do item automaticamente se houver item para editar
window.addEventListener('DOMContentLoaded', function() {
    if (window.itemParaEditar) {
        document.getElementById('adicionarItem').style.display = 'flex';
    }
});
// Fim de mostrar o popup de edição do item automaticamento se houver item para editar

// Overlay da foto de perfil ao passar o mouse
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.foto-usuario').forEach(function(div) {
        div.addEventListener('mouseenter', function() {
            var overlay = div.querySelector('.foto-usuario-overlay');
            if (overlay) overlay.style.display = 'flex';
        });
        div.addEventListener('mouseleave', function() {
            var overlay = div.querySelector('.foto-usuario-overlay');
            if (overlay) overlay.style.display = 'none';
        });
    });
});
// Fim do overlay de perfil ao passar o mouse

// Relógio em tempo real
function atualizarRelogioTempoReal() {
    const agora = new Date();
    const horas = agora.getHours().toString().padStart(2, '0');
    const minutos = agora.getMinutes().toString().padStart(2, '0');
    const segundos = agora.getSeconds().toString().padStart(2, '0');
    const relogio = document.getElementById('relogio-tempo-real');
    if (relogio) {
        relogio.textContent = `${horas}:${minutos}:${segundos}`;
    }
}
setInterval(atualizarRelogioTempoReal, 1000);
atualizarRelogioTempoReal();
// Fim do relógio em tempo real

// Drag & Drop dos cards não está funcionando ainda
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('body.desenvolvimento .card').forEach(card => {
    card.addEventListener('dragstart', function(e) {
      e.dataTransfer.setData('cardId', this.dataset.id);
    });
  });

  document.querySelectorAll('body.desenvolvimento .kanban-col').forEach(col => {
    col.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.classList.add('drag-over');
    });
    col.addEventListener('dragleave', function(e) {
      this.classList.remove('drag-over');
    });
    col.addEventListener('drop', function(e) {
      e.preventDefault();
      this.classList.remove('drag-over');
      const cardId = e.dataTransfer.getData('cardId');
      const novoStatus = this.querySelector('.kanban-col-badge').textContent.trim();
      fetch('../Controller/QuadroProjetoController.php', {
        method: 'POST',
        body: new URLSearchParams({
          acao: 'mover',
          idCard: cardId,
          novoStatus: novoStatus,
          idProjeto: idProjeto
        })
      }).then(() => location.reload());
    });
  });
});
// Fim do Drag & Drop