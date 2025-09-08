<?php
    require_once (__DIR__ . "/../Model/Tag.class.php");

    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

    switch ($acao) {
        case 'buscar':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $tags = Tag::listar(1, $id);
            if (empty($tags)) {
                echo json_encode(['erro' => 'Tag não encontrado']);
                exit;
            }

            $tag = $tags[0];
            echo json_encode([
                'id' => $tag->getId(),
                'nome' => $tag->getNome(),
                'cor' => $tag->getCor()
            ]);
            break;
        
        case 'adicionar':
            $nome = $_POST['nome'] ?? '';
            $cor = $_POST['cor'] ?? '';

            $tag = new Tag(0, $nome, $cor);
            $resultado = $tag->inserir();

            if ($resultado) {
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=5');
                exit;
            }
            break;

        case 'alterar':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nome = $_POST['nome'] ?? '';
            $cor = $_POST['cor'] ?? '';

            $tag = new Tag($id, $nome, $cor);
            $resultado = $tag->alterar();

            if ($resultado) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['erro' => 'Erro ao alterar tag']);
            }
            break;

        case 'excluir':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $tags = Tag::listar(1, $id);
            if (empty($tags)) {
                echo json_encode(['erro' => 'Tag não encontrado']);
                exit;
            }

            $tag = $tags[0];
            $resultado = $tag->excluir();

            if ($resultado) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['erro' => 'Erro ao excluir tag']);
            }
            break;

        default:
            echo json_encode(['erro' => 'Ação inválida']);
    }
?>
