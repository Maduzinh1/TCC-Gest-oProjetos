<?php
    require_once (__DIR__ . "/../Model/Pasta.class.php");
    session_start();

    $idUsuario = $_SESSION['usuario_id'];
    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

    switch ($acao) {
        case 'buscar':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $pastas = Pasta::listar(1, $id);
            if (empty($pastas)) {
                echo json_encode(['erro' => 'Pasta não encontrada']);
                exit;
            }

            $pasta = $pastas[0];
            echo json_encode([
                'id' => $pasta->getId(),
                'nome' => $pasta->getNome(),
                'descricao' => $pasta->getDescricao(),
                'imagem' => $pasta->getImagem()
            ]);
            break;

        case 'adicionar':
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $imagem = null;

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'pasta_' . uniqid() . '.' . $ext;
                $caminho = './../../img/' . $nomeArquivo;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
                $imagem = $caminho;
            }

            $pasta = new Pasta(0, $nome, $descricao, $imagem, $idUsuario);
            $resultado = $pasta->inserir();

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
            $descricao = $_POST['descricao'] ?? '';
            $imagem = null;

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'pasta_' . uniqid() . '.' . $ext;
                $caminho = './../../img/' . $nomeArquivo;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
                $imagem = $caminho;
            }

            $pastas = Pasta::listar(1, $id);
            if (empty($pastas)) {
                header('Location: ../View/index.php?erro=10');
                exit;
            }

            $pasta = $pastas[0];
            $pasta->setNome($nome);
            $pasta->setDescricao($descricao);
            if ($imagem) {
                $pasta->setImagem($imagem);
            }

            $resultado = $pasta->alterar();

            if ($resultado) {
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=11');
                exit;
            }
            break;

        case 'excluir':
            $id = $_GET['id'] ?? 0;
            if ($id <= 0) {
                header('Location: ../View/index.php');
                exit;
            }

            $pastas = Pasta::listar(1, $id);
            if (empty($pastas)) {
                header('Location: ../View/index.php?erro=12');
                exit;
            }

            $pasta = $pastas[0];
            $resultado = $pasta->excluir();

            if ($resultado) {
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=13');
                exit;
            }
            break;

        default:
            header('Location: ../View/index.php?erro=9');
            exit;
    }
?>