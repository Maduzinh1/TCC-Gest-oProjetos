<?php
    require_once (__DIR__."/../Classes/Tag.class.php");

    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

    switch ($acao) {
        case 'buscar':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $itens = Tag::listar(1, $id);
            if (empty($itens)) {
                echo json_encode(['erro' => 'Item não encontrado']);
                exit;
            }

            $item = $itens[0];
            echo json_encode([
                'id' => $item->getId(),
                'nome' => $item->getNome(),
            ]);
            break;
        
        case 'adicionar':
            $nome = $_POST['nome'] ?? '';

            $item = new Tag(0, $nome);
            $resultado = $item->inserir();

            if ($resultado) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['erro' => 'Erro ao adicionar item']);
            }
            break;

        case 'alterar':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nome = $_POST['nome'] ?? '';

            $item = new Tag($id, $nome);
            $resultado = $item->alterar();

            if ($resultado) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['erro' => 'Erro ao alterar item']);
            }
            break;

        case 'excluir':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $itens = Tag::listar(1, $id);
            if (empty($itens)) {
                echo json_encode(['erro' => 'Item não encontrado']);
                exit;
            }

            $item = $itens[0];
            $resultado = $item->excluir();

            if ($resultado) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['erro' => 'Erro ao excluir item']);
            }
            break;

        default:
            echo json_encode(['erro' => 'Ação inválida']);
    }
?>
