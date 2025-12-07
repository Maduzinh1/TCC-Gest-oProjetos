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
              <div class="card">
                <?php if ($card->getImagem()) { ?>
                  <img src="<?php echo $card->getImagem(); ?>" alt="Imagem do Card" class="card-img">
                <?php } ?>
                <h4><?php echo htmlspecialchars($card->getTitulo()); ?></h4>
                <p><?php echo htmlspecialchars($card->getDescricao()); ?></p>
                <!-- Botão para mover card (exemplo, pode ser dropdown ou drag&drop) -->
                <form action="../Controller/QuadroProjetoController.php" method="post" style="margin-top:8px;">
                  <input type="hidden" name="acao" value="mover">
                  <input type="hidden" name="idCard" value="<?php echo $card->getId(); ?>">
                  <input type="hidden" name="idProjeto" value="<?php echo $idProjeto; ?>">
                  <select name="novoStatus" onchange="this.form.submit()">
                    <?php foreach ($colunas as $optStatus => $optLabel): ?>
                      <option value="<?php echo $optStatus; ?>" <?php if ($optStatus == $status) echo 'selected'; ?>>
                        <?php echo $optLabel; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="kanban-col-footer">
            <form action="../Controller/QuadroProjetoController.php" method="post" enctype="multipart/form-data" class="kanban-add-form">
              <input type="hidden" name="acao" value="adicionar">
              <input type="hidden" name="idProjeto" value="<?php echo $idProjeto; ?>">
              <input type="hidden" name="status" value="<?php echo $status; ?>">
              <input type="text" name="titulo" placeholder="Título do card" required>
              <input type="text" name="descricao" placeholder="Descrição" required>
              <input type="file" name="imagem" accept="image/*">
              <button type="submit" class="kanban-add-btn"><i class="fa-solid fa-plus"></i> Add a card</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="footer">
    <!-- <h1>footer</h1> -->
  </div>
</body>
</html>
