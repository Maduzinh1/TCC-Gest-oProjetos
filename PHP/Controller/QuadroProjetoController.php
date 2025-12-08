<?php
    require_once (__DIR__ . "/../Model/QuadroProjeto.class.php");
    require_once (__DIR__ . "/../Model/Projeto.class.php");
    session_start();

    $idUsuario = $_SESSION['usuario_id'];
    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    switch ($acao) {
        case 'adicionar':
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $status = $_POST['status'] ?? 'Planejamento';
            $idProjeto = $_POST['idProjeto'] ?? 0;
            $imagem = null;

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'card_' . uniqid() . '.' . $ext;
                $caminho = './../../img/' . $nomeArquivo;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
                $imagem = $caminho;
            }

            $projetos = Projeto::listar(1, $idProjeto);
            if (empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=1");
                exit;
            }

            $card = new QuadroProjeto(null, $titulo, $descricao, $imagem, $status, $idProjeto);
            if ($card->inserir()) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto");
                exit;
            } else {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=2");
                exit;
            }
            break;

        case 'mover':
            $idCard = $_POST['idCard'] ?? 0;
            $novoStatus = $_POST['novoStatus'] ?? '';
            $idProjeto = $_POST['idProjeto'] ?? 0;

            $cards = QuadroProjeto::listar(1, $idCard);
            $projetos = Projeto::listar(1, $idProjeto);
            if (empty($cards) || empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=3");
                exit;
            }

            $card = $cards[0];
            $card->setStatus($novoStatus);
            if ($card->alterar()) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto");
                exit;
            } else {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=4");
                exit;
            }
            break;

        case 'excluir':
            $idCard = $_GET['idCard'] ?? 0;
            $idProjeto = $_GET['idProjeto'] ?? 0;

            $cards = QuadroProjeto::listar(1, $idCard);
            $projetos = Projeto::listar(1, $idProjeto);
            if (empty($cards) || empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=5");
                exit;
            }

            $card = $cards[0];
            if ($card->excluir()) {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto");
                exit;
            } else {
                header("Location: ../View/desenvolvimento.php?id=$idProjeto&erro=6");
                exit;
            }
            break;

        case 'ver':
            // Retorna HTML do conteúdo do card para o popup (AJAX)
            $idCard = $_GET['idCard'] ?? 0;
            $idProjeto = $_GET['idProjeto'] ?? 0;

            $cards = QuadroProjeto::listar(1, $idCard);
            $projetos = Projeto::listar(1, $idProjeto);
            if (empty($cards) || empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                http_response_code(403);
                echo "<div class='popup-header'><span>Sem acesso</span></div>";
                echo "<div class='popup-body'>Você não tem permissão para visualizar este card.</div>";
                exit;
            }

            $card = $cards[0];
            header('Content-Type: text/html; charset=UTF-8');
            ?>
            <div class="popup-header">
              <span class="close" onclick="fecharPopupCard()">
                <i class="fa-solid fa-xmark"></i>
              </span>
              <h3><?php echo htmlspecialchars($card->getTitulo()); ?></h3>
            </div>
            <div class="popup-body">
              <?php if ($card->getImagem()) { ?>
                <img src="<?php echo htmlspecialchars($card->getImagem()); ?>" alt="Imagem do Card" style="max-width:100%; border-radius:8px; margin-bottom:12px;">
              <?php } ?>
              <p style="text-align:left; white-space:pre-wrap;"><?php echo htmlspecialchars($card->getDescricao()); ?></p>
              <div style="margin-top:12px; text-align:left;">
                <span style="font-size:0.9rem; color:#666;">Status: <?php echo htmlspecialchars($card->getStatus()); ?></span>
              </div>
            </div>
            <?php
            exit;

        default:
            header('Location: ../View/projeto.php');
            exit;
    }
?>