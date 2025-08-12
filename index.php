<?php
  require_once (__DIR__."/PHP/Classes/Projeto.class.php");
  setlocale(LC_TIME, 'portuguese'); 
  date_default_timezone_set('America/Sao_Paulo');
  $busca = isset($_GET['busca'])?$_GET['busca']:0;
  $tipo = isset($_GET['tipo'])?$_GET['tipo']:0;
 
  $projetos = Projeto::listar($tipo, $busca);

  $projetos_array = [];
  foreach ($projetos as $projeto) {
      $projetos_array[] = [
          'id' => $projeto->getId() ?? '',
          'nome' => $projeto->getNome() ?? '',
          'descricao' => $projeto->getDescricao() ?? '',
          'tag' => $projeto->getTag() ?? '',
          'data_inicio' => $projeto->getDataInicio() ?? '',
          'data_fim' => $projeto->getDataFim() ?? '',
          'status' => $projeto->getStatus() ?? '',
          'urgencia' => $projeto->getUrgencia() ?? ''
      ];
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CSS Grid Layout</title>
  <link rel="stylesheet" href="./CSS/estilo.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <script src="./JavaScript/script.js" defer></script>
  <script defer> const projetos = <?php echo json_encode($projetos_array); ?>; </script>
</head>
<body class="body">

  <div class="header">
    <h1>header</h1>
  </div>

  <div class="banner">
    <figure>
      <img src="img/ceu.jpg"  width="100%" height="300">
    </figure>
  </div>

  <div class="preContent">
    <div class="content">

      <div class="Bloco-1">
        <div class="Bloco-relogio">
          <div id="relogio" class="relogio">--:--</div>
        </div>
        <div class="naosei1">naosei1</div>
      </div>

      <div class="Bloco-2">
        <div class="naosei2"></div>
        <div class="Bloco-calendario">
          <div class="titulo-calendario">
            <h2 id="mes-do-ano"></h2>
            <div>
              <button type="button" onclick="prevMes()">Anterior</button>
              <button type="button" onclick="nextMes()">Próximo</button>
            </div>
          </div>
          <table id="tabela-calendario" class="calendario"></table>
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

  <!-- conteudo invisivel alterado com js -->
  <div id="adicionarProjeto" class="popup">
    <div class="popup-content">
      <div class="popup-header">
        <span class="close" onclick="fecharPopupAddProjeto()">&times;</span>
      </div>
      <form id="eventForm" class="formulario-projeto" action="PHP/Projeto/Projeto.php" method="POST">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Projeto</legend>
          <input type="hidden" id="id" name="id" value="">
          <div class="formulario-campo campo-titulo">
            <label for="nome-projeto" class="formulario-label">Nome do projeto:</label>
            <input type="text" id="nome-projeto" class="formulario-input input-titulo" name="nome" required>
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-projeto" class="formulario-label">Descrição do projeto:</label>
            <textarea id="descricao-projeto" class="formulario-textarea input-descricao" name="descricao" required></textarea>
          </div>
            
          <div class="formulario-campo">
            <label for="data_inicio-projeto" class="formulario-label">Data de início:</label>
            <input type="date" id="data_inicio-projeto" class="formulario-input" name="data_inicio" required>
          </div>
            
          <div class="formulario-campo">
            <label for="data_fim-projeto" class="formulario-label">Data de término:</label>
            <input type="date" id="data_fim-projeto" class="formulario-input" name="data_fim" required>
          </div>

          <div class="formulario-campo">
            <label for="tags-projeto" class="formulario-label">Tags do projeto:</label>
            <select id="tags-projeto" class="formulario-input input-select" name="tag" required>
              <option value="A fazer">A fazer</option>
              <option value="Fazendo">Fazendo</option>
              <option value="Concluído">Concluído</option>
            </select>
          </div>
            
          <div class="formulario-campo">
            <label for="status-projeto" class="formulario-label">Status do projeto:</label>
            <select id="status-projeto" class="formulario-input input-select" name="status" required>
              <option value="A fazer">A fazer</option>
              <option value="Fazendo">Fazendo</option>
              <option value="Concluído">Concluído</option>
            </select>
          </div>

          <div class="formulario-campo">
            <label for="urgencia-projeto" class="formulario-label">Urgência do projeto:</label>
            <select id="urgencia-projeto" class="formulario-input input-select" name="urgencia" required>
              <option value="Baixa">Baixa</option>
              <option value="Média">Média</option>
              <option value="Alta">Alta</option>
            </select>
          </div>

          <button id="form-btn-projeto" type="submit" class="formulario-btn" name="acao" value="salvar">Adicionar Projeto</button>
        </fieldset>
      </form>
    </div>
  </div>
  
  <div id="popupProjetosDoDia" class="popup">
    <div id="popupProjetosDoDiaContent" class="popup-content">
      <!-- Conteúdo preenchido via JS -->
    </div>
  </div>

</body>
</html>
