<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./CSS/estilo.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/325fb60df1.js" crossorigin="anonymous"></script>
  <script src="./JavaScript/script.js" defer></script>
</head>
<body class="body">

  <div class="header">
    <h1>header</h1>
  </div>

  <div class="banner">
    <figure>
      <img src="img/ceu.jpg">
    </figure>
  </div>

  <div class="preContent">
    <div class="content">

      <div class="Bloco-1">
        <div class="Bloco-temporizador">
          <div id="temporizador" class="temporizador">00:00:00</div>
          <div class="temporizador-btn">
            <button class="definir-tempo" onclick="definirTemporizador()">
              <i class="fa-solid fa-sliders"></i>
            </button>
            <button id="btn-iniciar-pausar" class="iniciar-tempo" onclick="alternarTemporizador()">
              <i class="fa-solid fa-play"></i>
            </button>
            <button class="resetar-tempo" onclick="resetarTemporizador()">
              <i class="fa-solid fa-stop"></i>
            </button>
          </div>
        </div>
        <div class="naosei1">naosei1</div>
      </div>

      <div class="Bloco-2">
        <div class="naosei2">
          <a href="#" id="btn-calendario">Calendário</a>
          <a href="#" id="btn-tags">Tags</a>
        </div>
        <div id="Bloco-calendario" class="Bloco-calendario">
          <div class="titulo-calendario">
            <h2 id="mes-do-ano"></h2>
            <div class="titulo-calendario-btn">
              <button type="button" class="prevMes" onclick="prevMes()">
                <i class="fa-solid fa-angle-left"></i>
              </button>
              <button type="button" class="nextMes" onclick="nextMes()">
                <i class="fa-solid fa-angle-right"></i>
              </button>
            </div>
          </div>
          <table id="tabela-calendario" class="calendario">
            <!-- Conteúdo preenchido via JS e PHP -->
          </table>
        </div>

        <div id="Bloco-tags" class="Bloco-tags">
          <div class="titulo-tags">
            <h2>Lista de tags</h2>
            <button type="button" class="addTag" onclick="abrirPopupAddTag(event)">
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
          <table id="tabela-tags" class="tags">
            <!-- Conteúdo preenchido via JS e PHP -->
          </table>
        </div>
      </div>
      
      <div class="Bloco-3">
        <div class="Bloco-infousuario">
          <div class="foto-usuario">
            <figure>
              <img src="img/perfil.jpg" alt="Foto do Usuário" class="foto-usuario-img">
            </figure>
          </div>
          <div class="informacoes-usuario">
            <h1>Informações do Usuário</h1>
            <p>Nome:</p>
            <p>Email:</p>
            <p>Telefone:</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footer">
    <h1>footer</h1>
  </div>

  <!-- Conteúdo invisível alterado com JS -->
  <div id="adicionarItem" class="popup">
    <div class="popup-content">
      <div class="popup-header-form">
        <span class="close" onclick="fecharPopupAddItem()">
          <i class="fa-solid fa-xmark"></i>
        </span>
      </div>
      <form id="itemForm" class="formulario-item" onsubmit="salvarItem(event)">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Item</legend>
          <input type="hidden" id="id" name="id" value="">
          <div class="formulario-campo campo-titulo">
            <label for="nome-item" class="formulario-label">Nome:</label>
            <input type="text" id="nome-item" class="formulario-input input-titulo" name="nome" required>
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-item" class="formulario-label">Descrição:</label>
            <textarea id="descricao-item" class="formulario-textarea input-descricao" name="descricao" required></textarea>
          </div>
            
          <div class="formulario-campo">
            <label for="data_inicio-item" class="formulario-label">Data de início:</label>
            <input type="date" id="data_inicio-item" class="formulario-input" name="data_inicio" required>
          </div>
            
          <div class="formulario-campo">
            <label for="data_fim-item" class="formulario-label">Data de término:</label>
            <input type="date" id="data_fim-item" class="formulario-input" name="data_fim" required>
          </div>
            
          <div class="formulario-campo">
            <label for="status-item" class="formulario-label">Status:</label>
            <select id="status-item" class="formulario-input input-select" name="status" required>
              <option value="A fazer">A fazer</option>
              <option value="Fazendo">Fazendo</option>
              <option value="Concluído">Concluído</option>
            </select>
          </div>

          <div class="formulario-campo">
            <label for="urgencia-item" class="formulario-label">Urgência:</label>
            <select id="urgencia-item" class="formulario-input input-select" name="urgencia" required>
              <option value="Baixa">Baixa</option>
              <option value="Média">Média</option>
              <option value="Alta">Alta</option>
            </select>
          </div>

          <button id="form-btn-item" type="submit" class="formulario-btn" name="acao" value="adicionar">Adicionar item</button>
        </fieldset>
      </form>
    </div>
  </div>
  
  <div id="popupItemsDoDia" class="popup">
    <div id="popupItemsDoDiaContent" class="popup-content">
      <!-- Conteúdo preenchido via JS -->
    </div>
  </div>

  <!-- Conteúdo invisível alterado com JS -->
  <div id="adicionarTag" class="popup">
    <div class="popup-content">
      <div class="popup-header-form">
        <span class="close" onclick="fecharPopupAddTag()">
          <i class="fa-solid fa-xmark"></i>
        </span>
      </div>
      <form id="tagForm" class="formulario-tag" onsubmit="salvarTag(event)">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Tag</legend>
          <input type="hidden" id="id" name="id" value="">

          <div class="formulario-campo campo-titulo">
            <label for="nome-tag" class="formulario-label">Nome:</label>
            <input type="text" id="nome-tag" class="formulario-input input-titulo" name="nome" required>
          </div>

          <div class="formulario-campo ">
            <label for="cor-tag" class="formulario-label">Cor:</label>
            <input type="color" id="cor-tag" class="formulario-input input-cor" name="cor" required>
          </div>

          <button id="form-btn-tag" type="submit" class="formulario-btn" name="acao" value="adicionar">Adicionar tag</button>
        </fieldset>
      </form>
    </div>
  </div>

</body>
</html>
