<?php
  require_once(__DIR__ . "/../Model/Usuario.class.php");
  require_once(__DIR__ . "/../Model/Config.class.php");
  require_once(__DIR__ . "/../Model/Projeto.class.php");
  require_once(__DIR__ . "/../Model/QuadroProjeto.class.php");
  session_start();

  if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
  }

  $usuario = null;
  if (isset($_SESSION['usuario_id'])) {
    $usuarios = Usuario::listar(1, $_SESSION['usuario_id']);
    if ($usuarios) {
      $usuario = $usuarios[0];
    }
  }

  $config = Config::listar(1, $_SESSION['usuario_id'])[0];

  $idProjeto = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $projeto = Projeto::listar(1, $idProjeto);

  if (!$projeto || $projeto[0]->getIdUsuario() != $_SESSION['usuario_id']) {
    echo "<h2>Projeto não encontrado ou acesso negado.</h2>";
    exit;
  }

  $cards = QuadroProjeto::listar(3, $idProjeto);

  $kanban = [
    'Planejamento' => [],
    'Iniciado' => [],
    'Desenvolvendo' => [],
    'Concluído' => []
  ];
  foreach ($cards as $card) {
    $kanban[$card->getStatus()][] = $card;
  }
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
  <script> var idProjeto = <?php echo json_encode($idProjeto); ?>; </script>
  <script src="./../../JavaScript/script.js" defer></script>
</head>
<body class="body desenvolvimento">
  <div class="header">
    <a class="tab-item tab-item-0" href="./index.php">Estudos</a>
    <a class="tab-item tab-item-1" href="./projeto.php">Desenvolvimento de Projetos</a>
  </div>

  <div class="preContent">
    <div class="content">
      <div class="kanban">
        <?php
        $colunas = [
          'Planejamento' => 'Planejamento',
          'Iniciado' => 'Iniciado',
          'Desenvolvendo' => 'Desenvolvendo',
          'Concluído' => 'Concluído'
        ];
        foreach ($colunas as $status => $label): ?>
        <div class="kanban-col <?php echo strtolower($status); ?>">
          <div class="kanban-col-badge">
            <?php echo $label; ?>
          </div>
          <div class="kanban-col-body">
            <?php foreach ($kanban[$status] as $card): ?>
              <div class="card" draggable="true" data-id="<?php echo $card->getId(); ?>" onclick="abrirPopupCard(<?php echo $card->getId(); ?>, <?php echo $idProjeto; ?>, event)">
                <?php if ($card->getImagem()) { ?>
                  <img src="<?php echo $card->getImagem(); ?>" alt="Imagem do Card" class="card-img">
                <?php } ?>
                <h4><?php echo htmlspecialchars($card->getTitulo()); ?></h4>
                <div>
                  <form class="form-mover-card" action="../Controller/QuadroProjetoController.php" method="post" style="margin-top:8px;" onclick="event.stopPropagation()">
                    <input type="hidden" name="idCard" value="<?php echo $card->getId(); ?>">
                    <input type="hidden" name="idProjeto" value="<?php echo $idProjeto; ?>">
                    <input type="hidden" name="acao" value="mover">
                    <select name="novoStatus" style="margin-right:6px;">
                      <?php foreach ($colunas as $statusOpt => $labelOpt): ?>
                        <?php if ($statusOpt !== $card->getStatus()): ?>
                          <option value="<?php echo $statusOpt; ?>"><?php echo $labelOpt; ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-mover-card">Mover</button>
                  </form>
                  <a href="../Controller/QuadroProjetoController.php?idCard=<?php echo $card->getId(); ?>&idProjeto=<?php echo $idProjeto; ?>&acao=excluir" class="btn-excluir-card" onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este card?')">Excluir</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="kanban-col-footer">
            <button type="submit" class="kanban-add-btn" onclick="abrirPopupAddCard('<?php echo $status; ?>', <?php echo $idProjeto; ?>)"><i class="fa-solid fa-plus"></i> Add a card</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="footer">
    <!-- <h1>footer</h1> -->
  </div>

  <!-- Conteúdo invisível alterado com JS -->
  <div id="adicionarCard" class="popup">
    <div class="popup-content">
      <div class="popup-header-form">
        <span class="close" onclick="fecharPopupAddCard()">
          <i class="fa-solid fa-xmark"></i>
        </span>
      </div>
      <form id="cardForm" class="formulario-card" action="../Controller/QuadroProjetoController.php" method="post" enctype="multipart/form-data">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend-card">Adicionar Card</legend>
          <input type="hidden" id="idProjeto-card" name="idProjeto" value="">
          <input type="hidden" id="status-card" name="status" value="">
          <input type="hidden" name="acao" value="adicionar">

          <div class="formulario-campo campo-titulo">
            <label for="titulo-card" class="formulario-label">Título:</label>
            <input type="text" id="titulo-card" class="formulario-input input-titulo" name="titulo" required>
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-card" class="formulario-label">Descrição:</label>
            <textarea id="descricao-card" class="formulario-textarea input-descricao" name="descricao" required></textarea>
          </div>

          <div class="formulario-campo campo-imagem">
            <label for="imagem-card" class="formulario-label">Imagem:</label>
            <div class="custom-file-wrapper">
              <input type="file" id="imagem-card" class="formulario-input custom-file-input" name="imagem" accept="image/*">
              <label for="imagem-card" id="imagem-card-label" class="custom-file-label">
                <i class="fa-solid fa-image"></i> Escolher imagem
              </label>
            </div>
          </div>

          <button id="form-btn-card" type="submit" class="formulario-btn" name="acao" value="adicionar">Adicionar card</button>
        </fieldset>
      </form>
    </div>
  </div>

  <!-- Conteúdo invisível alterado com JS -->
  <div id="popupCard" class="popup popup-card">
    <div id="popupCardContent" class="popup-content">
      <!-- Conteúdo preenchido via JS -->
    </div>
  </div>

  <script>
    document.querySelectorAll('.kanban-col').forEach(function(col) {
      if (col.querySelector('.kanban-col-body').children.length === 0) {
        col.style.justifyContent = 'space-between';
      }
    });
  </script>
</body>
</html>
