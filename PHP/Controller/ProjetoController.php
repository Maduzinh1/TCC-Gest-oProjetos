<?php
    require_once (__DIR__ . "/../Model/Projeto.class.php");
    session_start();

    $idUsuario = $_SESSION['usuario_id'];
    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    switch ($acao) {
        case 'adicionar':
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            $projeto = new Projeto(null, $nome, $descricao, $idUsuario);
            if ($projeto->inserir()) {
                header('Location: ../View/projeto.php');
                exit;
            } else {
                header('Location: ../View/projeto.php?erro=1');
                exit;
            }
            break;

        case 'alterar':
            $id = $_POST['id'] ?? 0;
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            $projetos = Projeto::listar(1, $id);
            if (empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                header('Location: ../View/projeto.php?erro=2');
                exit;
            }

            $projeto = $projetos[0];
            $projeto->setNome($nome);
            $projeto->setDescricao($descricao);

            if ($projeto->alterar()) {
                header('Location: ../View/projeto.php');
                exit;
            } else {
                header('Location: ../View/projeto.php?erro=3');
                exit;
            }
            break;

        case 'excluir':
            $id = $_GET['id'] ?? 0;
            $projetos = Projeto::listar(1, $id);
            if (empty($projetos) || $projetos[0]->getIdUsuario() != $idUsuario) {
                header('Location: ../View/projeto.php?erro=4');
                exit;
            }
            $projeto = $projetos[0];
            if ($projeto->excluir()) {
                header('Location: ../View/projeto.php');
                exit;
            } else {
                header('Location: ../View/projeto.php?erro=5');
                exit;
            }
            break;

        default:
            header('Location: ../View/projeto.php');
            exit;
    }
?>