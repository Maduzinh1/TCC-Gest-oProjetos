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

        default:
            header('Location: ../View/projeto.php');
            exit;
    }
?>