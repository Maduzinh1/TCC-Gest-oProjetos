<?php
  require_once(__DIR__ . "/../Model/Usuario.class.php");
  require_once(__DIR__ . "/../Model/Config.class.php");
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
    <a class="tab-item tab-item-1 active" href="./desenvolvimento.php">Desenvolvimento</a>
  </div>

  <div class="preContent">
    <div class="content">
      <div class="kambam">
        <div class="kambam-col planejamento">Planejamento</div>
        <div class="kambam-col iniciado">Iniciado</div>
        <div class="kambam-col desenvolvendo">Desenvolvendo</div>
        <div class="kambam-col concluido">Concluído</div>
      </div>
    </div>
  </div>

  <div class="footer">
    <!-- <h1>footer</h1> -->
  </div>
</body>
</html>
