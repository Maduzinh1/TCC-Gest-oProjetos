<?php
  require_once(__DIR__ . "/../Model/Projeto.class.php");
  session_start();

  if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
  }

  $idUsuario = $_SESSION['usuario_id'];
  $projetos = Projeto::listar(3, $idUsuario);
  $projetosUsuario = [];
  foreach ($projetos as $projeto) {
    if ($projeto->getIdUsuario() == $idUsuario) {
      $projetosUsuario[] = $projeto;
    }
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
  <script src="./../../JavaScript/script.js" defer></script>
</head>
<body class="body projeto">

  <div class="header">
    <a class="tab-item tab-item-0" href="./index.php">Estudos</a>
    <a class="tab-item tab-item-1 active" href="./projeto.php">Desenvolvimento de Projetos</a>
  </div>

  <div class="preContent">
    <div class="content">
      <h2>Meus Projetos</h2>
      <div class="novo-projeto-wrapper">
        <button type="button" class="btn-novo-projeto" onclick="abrirPopupAddProjeto(event)"><i class="fa-solid fa-plus"></i> Novo Projeto</button>
      </div>
      <div class="projetos-lista">
        <table id="tabela-projetos">
          <?php
            $html = "
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Kanban</th>
                    <th>Excluir</th>
                </tr>
            ";
            foreach ($projetosUsuario as $projeto) {
                $html .= "
                    <tr>
                        <td>" . htmlspecialchars($projeto->getNome()) . "</td>
                        <td>" . htmlspecialchars($projeto->getDescricao()) . "</td>
                        <td>
                          <a href='desenvolvimento.php?id={$projeto->getId()}' class='btn-ver-kanban'>Ver Kanban</a>
                        </td>
                        <td>
                          <a href='../Controller/ProjetoController.php?id={$projeto->getId()}&acao=excluir' class='btn-excluir-projeto' onclick=\"return confirm('Tem certeza que deseja excluir este projeto?')\">Excluir</a>
                        </td>
                    </tr>
                ";
            }
            echo $html;
          ?>
        </table>
      </div>
    </div>
  </div>

  <div class="footer">
    <!-- <h1>footer</h1> -->
  </div>

  <!-- Conteúdo invisível alterado com JS -->
  <div id="adicionarProjeto" class="popup">
    <div class="popup-content">
      <div class="popup-header-form">
        <span class="close" onclick="fecharPopupAddProjeto()">
          <i class="fa-solid fa-xmark"></i>
        </span>
      </div>
      <form id="projetoForm" class="formulario-projeto" action="../Controller/ProjetoController.php" method="post" enctype="multipart/form-data">
        <fieldset class="formulario-fieldset">
          <legend id="form-legend">Adicionar Projeto</legend>
          <input type="hidden" id="id" name="id" value="">

          <div class="formulario-campo campo-titulo">
            <label for="nome-projeto" class="formulario-label">Nome:</label>
            <input type="text" id="nome-projeto" class="formulario-input input-titulo" name="nome" required>
          </div>

          <div class="formulario-campo campo-descricao">
            <label for="descricao-projeto" class="formulario-label">Descrição:</label>
            <textarea id="descricao-projeto" class="formulario-textarea input-descricao" name="descricao" required></textarea>
          </div>

          <button id="form-btn-projeto" type="submit" class="formulario-btn" name="acao" value="adicionar">Adicionar projeto</button>
        </fieldset>
      </form>
    </div>
  </div>
</body>
</html>