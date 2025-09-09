<?php
  require_once(__DIR__ . "/../Model/Tag.class.php");
  require_once(__DIR__ . "/../Model/Calendario.class.php");
  require_once(__DIR__ . "/../Model/Usuario.class.php");
  require_once(__DIR__ . "/../Model/Config.class.php");
  session_start();

  if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
  }

  $tags = Tag::listar(0, 0);

  $item = null;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $itens = Calendario::listar(1, $id);
    if ($itens) {
      $item = $itens[0];
    }
  }

  $usuario = null;
  if (isset($_SESSION['usuario_id'])) {
    $usuarios = Usuario::listar(1, $_SESSION['usuario_id']);
    if ($usuarios) {
      $usuario = $usuarios[0];
    }
  }

  $config = Config::listar(1, $_SESSION['usuario_id'])[0];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./../../CSS/estilo.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/325fb60df1.js" crossorigin="anonymous"></script>
  <script> window.itemParaEditar = <?php if (isset($item)) { echo 'true'; } else { echo 'false'; } ?>; </script>
  <script src="./../../JavaScript/script.js" defer></script>
</head>
<body class="body">

  <div class="header">
    <h1>header</h1>
  </div>

  <div class="banner">
    <figure style="margin:0;">
      <?php if ($config && $config->getBanner()) { ?>
        <img id="banner-img" src="<?php echo $config->getBanner(); ?>" alt="Banner">
      <?php } else { ?>
        <div id="banner-img" style="width:100%;height:300px;display:flex;align-items:center;justify-content:center;background:#eee;color:#888;font-size:24px;">
          Banner
        </div>
      <?php } ?>
      <form id="form-banner" action="../Controller/UsuarioController.php" method="post" enctype="multipart/form-data" style="position:absolute;top:10px;right:10px;">
        <input type="hidden" name="acao" value="alterar_banner">
        <input type="file" id="input-banner" name="banner" accept="image/*" style="display:none" onchange="this.form.submit()">
        <label for="input-banner" style="cursor:pointer;">
          <span class="btn-banner-editar" style="background:rgba(0,0,0,0.5);border-radius:50%;padding:8px;">
            <i class="fa-solid fa-pen"></i>
          </span>
        </label>
      </form>
    </figure>
  </div>

  <div class="preContent">
    <div class="content">
      <div class="Linha-1">
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
              <form id="form-foto-usuario" action="../Controller/UsuarioController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="alterar_foto">
                <input type="file" id="input-foto-usuario" name="foto" accept="image/*" style="display:none" onchange="this.form.submit()">

                <label for="input-foto-usuario">
                  <?php if ($usuario && $usuario->getFotoPerfil()) { ?>
                    <img src="<?php echo $usuario->getFotoPerfil(); ?>" alt="Foto do Usuário" class="foto-usuario-img">
                  <?php } else { ?>
                    <div class="foto-usuario-placeholder">
                      Foto de perfil
                    </div>
                  <?php } ?>
                  <div class="foto-usuario-overlay">
                    Clique para alterar
                  </div>
                </label>
              </form>
            </div>
            <div class="informacoes-usuario">
              <h1>Perfil</h1>
              <p>Nome: <?php if ($usuario) { echo $usuario->getNome(); } ?></p>
              <p>Email: <?php if ($usuario) { echo $usuario->getEmail(); } ?></p>
              <a href="../Controller/UsuarioController.php?acao=deslogar" class="btn-logout">Sair</a>
            </div>
          </div>
        </div>
      </div>
      <div class="Linha-2">
        <div class="Bloco-4">
          <div class="Bloco-pastas">
            <div class="Pastas-titulo">
              Pastas
              <button type="button" class="addPasta" onclick="abrirPopupAddPasta(event)">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
            <div class="Pastas-conteudo">
              <div class="Pasta1">
                <div class="ImageSide">
                  <img src="./../../img/perfil.jpg" alt="Pasta 1">
                </div>
                <div class="DescSide">
                  <h3>Pasta 1</h3>
                  <p>Descrição da Pasta 1</p>
                </div>
              </div>
              <div class="conteudo2">conteudo2</div>
              <div class="conteudo3">conteudo3</div>
              <div class="conteudo4">conteudo4</div>
              <div class="conteudo5">conteudo5</div>
              <div class="conteudo6">conteudo6</div>
              <div class="conteudo7">conteudo7</div>
              <div class="conteudo8">conteudo8</div>
            </div>
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
      <form id="itemForm" class="formulario-item" action="./../Controller/CalendarioController.php" method="post">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Item</legend>
          <input type="hidden" id="id" name="id" value="<?php if (isset($item)) { echo $item->getId(); } ?>">

          <div class="formulario-campo campo-titulo">
            <label for="nome-item" class="formulario-label">Nome:</label>
            <input type="text" id="nome-item" class="formulario-input input-titulo" name="nome" required value="<?php if (isset($item)) { echo $item->getNome(); } ?>">
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-item" class="formulario-label">Descrição:</label>
            <textarea id="descricao-item" class="formulario-textarea input-descricao" name="descricao" required><?php if (isset($item)) { echo $item->getDescricao(); } ?></textarea>
          </div>
            
          <div class="formulario-campo">
            <label for="data_inicio-item" class="formulario-label">Data de início:</label>
            <input type="date" id="data_inicio-item" class="formulario-input" name="data_inicio" required value="<?php if (isset($item)) { echo $item->getDataInicio(); } ?>">
          </div>
            
          <div class="formulario-campo">
            <label for="data_fim-item" class="formulario-label">Data de término:</label>
            <input type="date" id="data_fim-item" class="formulario-input" name="data_fim" required value="<?php if (isset($item)) { echo $item->getDataFim(); } ?>">
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

          <div class="formulario-campo">
            <label for="tag-item" class="formulario-label">Tag:</label>
            <select id="tag-item" class="formulario-input input-select" name="tag_id" required>
              <?php foreach ($tags as $tag) { ?>
                <option value="<?php echo $tag->getId(); ?>"><?php echo $tag->getNome(); ?></option>
              <?php } ?>
            </select>
          </div>

          <button id="form-btn-item" type="submit" class="formulario-btn" name="acao" value="<?php  if (isset($item)) { echo 'alterar'; } else { echo 'adicionar'; } ?>"> <?php if (isset($item)) { echo 'Alterar item'; } else { echo 'Adicionar item'; } ?></button>
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
      <form id="tagForm" class="formulario-tag" action="./../Controller/TagController.php" method="post">
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

  <!-- Conteúdo invisível alterado com JS -->
  <div id="adicionarPasta" class="popup">
    <div class="popup-content">
      <div class="popup-header-form">
        <span class="close" onclick="fecharPopupAddPasta()">
          <i class="fa-solid fa-xmark"></i>
        </span>
      </div>
      <form id="pastaForm" class="formulario-pasta" onsubmit="salvarPasta(event)" enctype="multipart/form-data">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Pasta</legend>
          <input type="hidden" id="id" name="id" value="">

          <div class="formulario-campo campo-titulo">
            <label for="nome-pasta" class="formulario-label">Nome:</label>
            <input type="text" id="nome-pasta" class="formulario-input input-titulo" name="nome" required>
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-pasta" class="formulario-label">Descrição:</label>
            <textarea id="descricao-pasta" class="formulario-textarea input-descricao" name="descricao" required></textarea>
          </div>

          <div class="formulario-campo campo-imagem">
            <label for="imagem-pasta" class="formulario-label">Imagem:</label>
            <div class="custom-file-wrapper">
              <input type="file" id="imagem-pasta" class="formulario-input custom-file-input" name="imagem" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
              <label for="imagem-pasta" id="imagem-pasta-label" class="custom-file-label">
                <i class="fa-solid fa-image"></i> Escolher imagem
              </label>
            </div>
          </div>

          <button id="form-btn-pasta" type="submit" class="formulario-btn" name="acao" value="adicionar">Adicionar pasta</button>
        </fieldset>
      </form>
    </div>
  </div>
</body>
</html>
